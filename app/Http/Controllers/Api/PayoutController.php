<?php

namespace App\Http\Controllers\Api;

use App\Enums\PayoutStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService,
    ) {}

    /**
     * List payouts (role-aware).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Payout::with(['restaurant', 'rider'])->latest();

        if ($user->isRestaurantOwner()) {
            $restaurantIds = $user->restaurantsOwned()->pluck('id');
            $query->whereIn('restaurant_id', $restaurantIds);
        } elseif ($user->isRider()) {
            $query->where('rider_id', $user->id);
        }
        // Admin sees all

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $payouts = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => PayoutResource::collection($payouts),
            'meta'    => [
                'current_page' => $payouts->currentPage(),
                'last_page'    => $payouts->lastPage(),
                'total'        => $payouts->total(),
            ],
        ]);
    }

    /**
     * Admin: mark a payout as paid.
     */
    public function markPaid(Request $request, int $id): JsonResponse
    {
        $payout = Payout::findOrFail($id);

        if ($payout->status !== PayoutStatus::Pending) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending payouts can be marked as paid.',
            ], 422);
        }

        $payout->update([
            'status'  => PayoutStatus::Completed,
            'paid_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout marked as paid.',
            'data'    => new PayoutResource($payout->refresh()->load(['restaurant', 'rider'])),
        ]);
    }
}

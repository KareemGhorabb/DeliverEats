<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'status'               => $this->status->value,
            'status_label'         => $this->status->label(),

            'customer'             => new UserResource($this->whenLoaded('user')),
            'restaurant'           => new RestaurantResource($this->whenLoaded('restaurant')),
            'rider'                => new UserResource($this->whenLoaded('rider')),

            'items'                => OrderItemResource::collection($this->whenLoaded('items')),
            'payment'              => new PaymentResource($this->whenLoaded('payment')),

            'subtotal'             => (float) $this->subtotal,
            'delivery_fee'         => (float) $this->delivery_fee,
            'surge_multiplier'     => (float) $this->surge_multiplier,
            'tax'                  => (float) $this->tax,
            'total'                => (float) $this->total,
            'is_reviewed'          => $this->reviews()->exists(),
            'delivery_address'     => $this->delivery_address,
            'delivery_lat'         => $this->delivery_lat,
            'delivery_lng'         => $this->delivery_lng,
            'special_instructions' => $this->special_instructions,

            'confirmed_at'         => $this->confirmed_at?->toISOString(),
            'preparing_at'         => $this->preparing_at?->toISOString(),
            'ready_at'             => $this->ready_at?->toISOString(),
            'picked_up_at'         => $this->picked_up_at?->toISOString(),
            'delivered_at'         => $this->delivered_at?->toISOString(),
            'cancelled_at'         => $this->cancelled_at?->toISOString(),
            'cancellation_reason'  => $this->cancellation_reason,

            'payment_token'        => $this->when(isset($this->payment_token), $this->payment_token),
            'created_at'           => $this->created_at?->toISOString(),
            'updated_at'           => $this->updated_at?->toISOString(),
        ];
    }
}

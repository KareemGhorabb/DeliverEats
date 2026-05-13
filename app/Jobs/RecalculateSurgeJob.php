<?php

namespace App\Jobs;

use App\Services\SurgeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RecalculateSurgeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * Create a new job instance.
     *
     * @param  int  $restaurantId  The restaurant whose surge pricing needs recalculation
     */
    public function __construct(public readonly int $restaurantId)
    {
        // Route to the dedicated 'surge' Redis queue with lower priority
        $this->onQueue('surge');
    }

    /**
     * Execute the job.
     * Delegates to SurgeService which computes and caches the new multiplier.
     */
    public function handle(SurgeService $surgeService): void
    {
        $multiplier = $surgeService->recalculate($this->restaurantId);

        Log::info("RecalculateSurgeJob: Restaurant [{$this->restaurantId}] surge multiplier set to {$multiplier}x.");
    }
}


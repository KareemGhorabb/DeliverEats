<?php

namespace App\Jobs;

use App\Services\SurgeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculateSurgeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $restaurantId,
    ) {
        $this->onQueue('surge');
    }

    public function handle(SurgeService $surgeService): void
    {
        $surgeService->recalculate($this->restaurantId);
    }
}

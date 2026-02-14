<?php

namespace App\Jobs;

use App\Services\OrderService\OrderExternalSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PollExternalServiceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(OrderExternalSyncService $externalSyncService): void
    {
        $externalSyncService->sync();
    }
}

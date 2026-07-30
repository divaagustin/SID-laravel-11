<?php

namespace App\Jobs;

use App\Services\OpenDkSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncOpenDkJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60; // Wait 60 seconds before retrying on failure

    public function handle(OpenDkSyncService $openDkService): void
    {
        Log::info("Starting Scheduled OpenDK Synchronization Job...");

        if (! $openDkService->isConfigured()) {
            Log::warning("OpenDK Sync Job skipped: OPENDK_URL or OPENDK_API_KEY not configured in .env.");
            return;
        }

        $result = $openDkService->sync();

        if (! $result['success']) {
            Log::error("OpenDK Sync Job failed: " . $result['message']);
            $this->fail(new \Exception($result['message']));
        } else {
            Log::info("OpenDK Sync Job completed successfully.");
        }
    }
}

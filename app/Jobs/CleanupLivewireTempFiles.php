<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CleanupLivewireTempFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle(): void
    {
        $directory = public_path('livewire-tmp');

        if (!File::isDirectory($directory)) {
            return;
        }

        $files = File::allFiles($directory);
        foreach ($files as $file) {
            // Delete files older than 1 hour to avoid deleting active uploads
            if (time() - $file->getMTime() > 3600) {
                File::delete($file->getRealPath());
            }
        }
    }
}

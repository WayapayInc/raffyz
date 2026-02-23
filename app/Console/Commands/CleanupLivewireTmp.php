<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupLivewireTmp extends Command
{
    protected $signature = 'livewire:cleanup-tmp';
    protected $description = 'Clean up old Livewire temporary files';

    public function handle()
    {
        $this->info('Cleaning up Livewire temporary files...');
        // Logic to clear temp files if needed
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OptimizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run optimize commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('optimize:clear');
        $this->call('optimize');

        Log::info("Cache clear command run.");
    }
}

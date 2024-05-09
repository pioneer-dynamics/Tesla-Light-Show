<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Contracts\LightShowService;
use Illuminate\Support\Facades\Storage;

class PruneTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-temp-files {--H|hours=24}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary uploaded files older than 24 hours (or as specified).';

    /**
     * Execute the console command.
     */
    public function handle(LightShowService $lightShowService)
    {
        $lightShowService->prune($this->option('hours'));

        $this->info(__('All temporary files older than :hour :hours pruned.', [
            'hour' => $this->option('hours'),
            'hours' => Str::plural('hour', (int)$this->option('hours'))
        ]));
    }
}

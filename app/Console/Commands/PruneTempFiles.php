<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
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
    public function handle()
    {
        foreach (Storage::disk('temp')->files() as $file) {
            $fileLastModified = Carbon::createFromTimestamp(Storage::disk('temp')->lastModified($file));
 
            if ($fileLastModified->addHours($this->option('hours'))->isPast())
            {
                Storage::disk('temp')->delete($file);
            }
        }
    }
}

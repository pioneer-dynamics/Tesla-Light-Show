<?php

namespace App\Providers;

use App\Services\ZipStream;
use Illuminate\Support\ServiceProvider;
use App\Contracts\ZipStream as ZipStreamContract;

class ZipStreamServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ZipStreamContract::class, ZipStream::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

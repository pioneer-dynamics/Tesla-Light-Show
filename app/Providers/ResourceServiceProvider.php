<?php

namespace App\Providers;

use App\Contracts\LightShowService as LightShowServiceContract;
use App\Services\LightShowService;
use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LightShowServiceContract::class, LightShowService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

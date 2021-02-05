<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StoreFilesService;
use Illuminate\Contracts\Cache\Store;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StoreFilesService::class, function ($app) {
            return new StoreFilesService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

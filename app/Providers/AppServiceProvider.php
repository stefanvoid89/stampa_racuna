<?php

namespace App\Providers;

use App\Services\SendMailsService;
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

        $this->app->singleton(SendMailsService::class, function ($app) {
            return new SendMailsService();
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

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Jobs\CreateFamily;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindMethod([CreateFamily::class, 'handle'], function ($job, $app) {
            return $job->handle();
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

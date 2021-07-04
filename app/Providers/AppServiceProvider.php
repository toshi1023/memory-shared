<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Jobs\CreateFamily;
use App\Jobs\DeleteFamily;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // CreateFamily jobの実行
        $this->app->bindMethod([CreateFamily::class, 'handle'], function ($job, $app) {
            return $job->handle();
        });
        // DeleteFamily jobの実行
        $this->app->bindMethod([DeleteFamily::class, 'handle'], function ($job, $app) {
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

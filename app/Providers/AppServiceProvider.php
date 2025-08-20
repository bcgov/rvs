<?php

namespace App\Providers;

use Override;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    #[Override]
    public function register()
    {
        if (config('app.env') === 'production' || config('app.env') === 'development') {
            $this->app['request']->server->set('HTTPS','on');
        }
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

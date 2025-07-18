<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        // Application Service Providers
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        // Lfp Module
        Modules\Lfp\Providers\LfpServiceProvider::class,
        Modules\Lfp\Providers\RouteServiceProvider::class,

        // Twp Module
        Modules\Twp\Providers\TwpServiceProvider::class,
        Modules\Twp\Providers\RouteServiceProvider::class,

        // Vss Module
        Modules\Vss\Providers\VssServiceProvider::class,
        Modules\Vss\Providers\RouteServiceProvider::class,

        // Neb Module
        Modules\Neb\Providers\NebServiceProvider::class,
        Modules\Neb\Providers\RouteServiceProvider::class,

        // Yeaf Module
        Modules\Yeaf\Providers\YeafServiceProvider::class,
        Modules\Yeaf\Providers\RouteServiceProvider::class,

        // Plsc Module
        Modules\Plsc\Providers\PlscServiceProvider::class,
        Modules\Plsc\Providers\RouteServiceProvider::class,

        // Package Service Providers
        Barryvdh\DomPDF\ServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Modules middlewares
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // YEAF
            'yeaf_admin' => \Modules\Yeaf\Http\Middleware\IsAdmin::class,
            'yeaf_active' => \Modules\Yeaf\Http\Middleware\IsActive::class,

            // TWP
            'twp_inertia' => \Modules\Twp\Http\Middleware\HandleInertiaRequests::class,
            'twp_admin' => \Modules\Twp\Http\Middleware\IsAdmin::class,
            'twp_active' => \Modules\Twp\Http\Middleware\IsActive::class,

            // VSS
            'vss_admin' => \Modules\Vss\Http\Middleware\IsAdmin::class,
            'vss_active' => \Modules\Vss\Http\Middleware\IsActive::class,

            // NEB
            'neb_admin' => \Modules\Neb\Http\Middleware\IsAdmin::class,
            'neb_active' => \Modules\Neb\Http\Middleware\IsActive::class,

            // LFP
            'lfp_admin' => \Modules\Lfp\Http\Middleware\IsAdmin::class,
            'lfp_active' => \Modules\Lfp\Http\Middleware\IsActive::class,

            // PLSC
            'plsc_admin' => \Modules\Plsc\Http\Middleware\IsAdmin::class,
            'plsc_active' => \Modules\Plsc\Http\Middleware\IsActive::class,

            'super_admin' => \App\Http\Middleware\SuperAdmin::class,
            'apiauth' => \App\Http\Middleware\ApiAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

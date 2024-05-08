<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            \App\Http\Middleware\HandleInertiaRequests::class,

        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'yeaf_admin' => \Modules\Yeaf\Http\Middleware\IsAdmin::class,
        'yeaf_active' => \Modules\Yeaf\Http\Middleware\IsActive::class,
        'twp_admin' => \Modules\Twp\Http\Middleware\IsAdmin::class,
        'twp_active' => \Modules\Twp\Http\Middleware\IsActive::class,
        'vss_admin' => \Modules\Vss\Http\Middleware\IsAdmin::class,
        'vss_active' => \Modules\Vss\Http\Middleware\IsActive::class,
        'neb_admin' => \Modules\Neb\Http\Middleware\IsAdmin::class,
        'neb_active' => \Modules\Neb\Http\Middleware\IsActive::class,
        'lfp_admin' => \Modules\Lfp\Http\Middleware\IsAdmin::class,
        'lfp_active' => \Modules\Lfp\Http\Middleware\IsActive::class,
        'plsc_admin' => \Modules\Plsc\Http\Middleware\IsAdmin::class,
        'plsc_active' => \Modules\Plsc\Http\Middleware\IsActive::class,
        'super_admin' => \App\Http\Middleware\SuperAdmin::class,

        'apiauth' => \App\Http\Middleware\ApiAuth::class,

    ];
}

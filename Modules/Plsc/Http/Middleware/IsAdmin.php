<?php

namespace Modules\Plsc\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):((Response | RedirectResponse)) $next
     * @param  string|null  ...$roles
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $roles = empty($roles) ? [null] : $roles;

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (! $user->hasRole(Role::SUPER_ADMIN) && ! $user->hasRole(Role::PLSC_ADMIN)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}

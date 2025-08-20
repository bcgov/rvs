<?php

namespace Modules\Yeaf\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):((Response | RedirectResponse)) $next
     * @param  string|null  ...$roles
     * @return \Inertia\Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $roles = empty($roles) ? [null] : $roles;

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->disabled || is_null($user->idir_user_guid)) {
            Auth::logout();

            return redirect()->route('login');
        }

        //active user must have at least a YEAF User role
        if (
            ! $user->hasRole(Role::SUPER_ADMIN) &&
            ! $user->hasRole(Role::YEAF_ADMIN) &&
            ! $user->hasRole(Role::YEAF_USER)
        ) {
            if (! $user->hasRole(Role::YEAF_GUEST)) {
                $role = Role::where('name', Role::YEAF_GUEST)->first();
                $user->roles()->attach($role);
            }


                return Inertia::render('Home', [
                'loginAttempt' => true,
                'hasAccess' => false,
                'status' => 'Please contact YEAF Admin to grant you access.',
            ]);
        }

        return $next($request);
    }
}

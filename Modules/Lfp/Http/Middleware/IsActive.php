<?php

namespace Modules\Lfp\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Lfp\Entities\Lfp;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$roles
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
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

        //active user must have at least a LFP User role
        if (! $user->hasRole(Role::SUPER_ADMIN) && ! $user->hasRole(Role::LFP_ADMIN) && ! $user->hasRole(Role::LFP_USER)) {
            if (! $user->hasRole(Role::LFP_GUEST)) {
                $role = Role::where('name', Role::LFP_GUEST)->first();
                $user->roles()->attach($role);
            }
            return Inertia::render('Home', [
                'loginAttempt' => true,
                'hasAccess' => false,
                'status' => 'Please contact LFP Admin to grant you access.',
            ]);
        }

        return $next($request);
    }
}

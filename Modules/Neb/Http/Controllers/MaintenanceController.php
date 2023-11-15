<?php

namespace Modules\Neb\Http\Controllers;

use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffList(Request $request): \Inertia\Response
    {
        $staff = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::NEB_ADMIN, Role::NEB_USER]);
        })->orderBy('created_at', 'desc')->get();

        foreach ($staff as $user) {
            if ($user->roles->contains('name', Role::NEB_ADMIN)) {
                $user->access_type = 'A';
            } else {
                $user->access_type = 'U';
            }
        }

        return Inertia::render('Neb::Staff', ['status' => true, 'results' => $staff]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): \Inertia\Response
    {
        if ($user->roles->contains('name', Role::NEB_ADMIN)) {
            $user->access_type = 'A';
        } else {
            $user->access_type = 'U';
        }

        return Inertia::render('Neb::StaffEdit', ['status' => true, 'results' => $user]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function staffEdit(StaffEditRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $user);

        $user->tele = $request->input('tele');
        //        $user->access_type = $request->access_type;
        $user->disabled = $request->disabled;
        $user->save();

        //reset roles
        $roles = Role::whereIn('name', [Role::NEB_ADMIN, Role::NEB_USER])->get();
        foreach ($roles as $role) {
            $user->roles()->detach($role);
        }

        //add user role

        //if admin add admin role
        if ($request->access_type == 'A') {
            $role = Role::where('name', Role::NEB_ADMIN)->first();
            $user->roles()->attach($role);
        } else {
            $role = Role::where('name', Role::NEB_USER)->first();
            $user->roles()->attach($role);
        }

        return Redirect::route('neb.staff.list');
    }
}

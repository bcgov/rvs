<?php

namespace Modules\Vss\Http\Controllers;

use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffList(Request $request): Response
    {
        $staff = User::with('roles')
            ->whereHas('roles', function ($q) {
                return $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER, Role::VSS_GUEST]);
            })->orderBy('created_at', 'desc')->get();

        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): Response
    {
        if ($user->roles->contains('name', Role::VSS_ADMIN)) {
            $user->access_type = 'A';
        } else {
            $user->access_type = 'U';
        }
        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function staffEdit(StaffEditRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $user->disabled = $request->disabled;
        $user->save();

        //reset roles
        $roles = Role::whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER, Role::VSS_GUEST])->get();
        foreach ($roles as $role) {
            $user->roles()->detach($role);
        }

        //add user role

        //if admin add admin role
        if ($request->access_type == 'A') {
            $role = Role::where('name', Role::VSS_ADMIN)->first();
            $user->roles()->attach($role);
        } else {
            $role = Role::where('name', Role::VSS_USER)->first();
            $user->roles()->attach($role);
        }

        return Redirect::route('vss.maintenance.staff.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index(): Response
    {
        return Inertia::render('Vss::Dashboard');
    }

}

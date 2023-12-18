<?php

namespace Modules\Lfp\Http\Controllers;

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
            return $q->whereIn('name', [Role::LFP_ADMIN, Role::LFP_USER, Role::LFP_GUEST]);
        })->orderBy('created_at', 'desc')->get();

        foreach ($staff as $user) {
            if ($user->roles->contains('name', Role::LFP_ADMIN)) {
                $user->access_type = 'A';
            } elseif ($user->roles->contains('name', Role::LFP_USER)) {
                $user->access_type = 'U';
            } else {
                $user->access_type = 'G';
            }
        }

        return Inertia::render('Lfp::Staff', ['status' => true, 'results' => $staff]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): \Inertia\Response
    {
        if ($user->roles->contains('name', Role::LFP_ADMIN)) {
            $user->access_type = 'A';
        } elseif ($user->roles->contains('name', Role::LFP_USER)) {
            $user->access_type = 'U';
        } else {
            $user->access_type = 'G';
        }

        return Inertia::render('Lfp::StaffEdit', ['status' => true, 'results' => $user]);
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
        $user->save();

        //reset roles
        $roles = Role::whereIn('name', [Role::LFP_ADMIN, Role::LFP_USER, Role::LFP_GUEST])->get();
        foreach ($roles as $role) {
            $user->roles()->detach($role);
        }

        //if admin add admin role
        if ($request->access_type == 'A') {
            $role = Role::where('name', Role::LFP_ADMIN)->first();
            $user->roles()->attach($role);
        }elseif ($request->access_type == 'U') {
            $role = Role::where('name', Role::LFP_USER)->first();
            $user->roles()->attach($role);
        } else {
            $role = Role::where('name', Role::LFP_GUEST)->first();
            $user->roles()->attach($role);
        }

        return Redirect::route('lfp.staff.list');
    }
}

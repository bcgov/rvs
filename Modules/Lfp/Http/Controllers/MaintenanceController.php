<?php

namespace Modules\Lfp\Http\Controllers;

use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Lfp\Entities\Util;
use Modules\Lfp\Http\Requests\UtilEditRequest;
use Modules\Lfp\Http\Requests\UtilStoreRequest;

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

        return Inertia::render('Lfp::Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
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

        return Inertia::render('Lfp::Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
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

        return Redirect::route('lfp.maintenance.staff.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function utilList(Request $request): \Inertia\Response
    {
        $utils = Util::orderBy('field_type', 'asc')->get();

        $cat_utils = [];
        $cat_titles = [];
        foreach ($utils as $util) {
            $cat_utils[$util->field_type][] = $util;
        }
        foreach ($cat_utils as $k=>$v){
            $cat_titles[] = $k;
        }

        return Inertia::render('Lfp::Maintenance', ['status' => true, 'results' => $cat_utils,
            'categories' => $cat_titles, 'page' => 'utils']);
    }


    /**
     * Update a utility resource.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function utilUpdate(UtilEditRequest $request, Util $util): \Illuminate\Http\RedirectResponse
    {
        $util->update($request->validated());

        return Redirect::route('lfp.maintenance.utils.list');
    }


    /**
     * Store a utility resource.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function utilStore(UtilStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        Util::create($request->validated());

        return Redirect::route('lfp.maintenance.utils.list');
    }

}

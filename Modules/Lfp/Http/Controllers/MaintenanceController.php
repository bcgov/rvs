<?php

namespace Modules\Lfp\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Lfp\Entities\Util;
use Modules\Lfp\Http\Requests\UtilEditRequest;
use Modules\Lfp\Http\Requests\UtilStoreRequest;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function staffList(Request $request): Response
    {
        $staff = User::with('roles')
            ->whereHas('roles', fn($q) => $q->whereIn('name', [Role::LFP_ADMIN, Role::LFP_USER, Role::LFP_GUEST]))->orderBy('created_at', 'desc')->get();

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
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function staffShow(Request $request, User $user): Response
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
     * @param StaffEditRequest $request
     * @param User $user
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function staffEdit(StaffEditRequest $request, User $user): RedirectResponse
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
     * @param Request $request
     *
     * @return Response
     */
    public function utilList(Request $request): Response
    {
        $utils = Util::orderBy('field_name', 'asc')->get();

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
     * @param UtilEditRequest $request
     * @param Util $util
     *
     * @return RedirectResponse
     */
    public function utilUpdate(UtilEditRequest $request, Util $util): RedirectResponse
    {
        $util->update($request->validated());

        return Redirect::route('lfp.maintenance.utils.list');
    }


    /**
     * Store a utility resource.
     *
     * @param UtilStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function utilStore(UtilStoreRequest $request): RedirectResponse
    {
        Util::create($request->validated());

        return Redirect::route('lfp.maintenance.utils.list');
    }

}

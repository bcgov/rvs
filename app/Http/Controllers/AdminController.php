<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjaxRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Response;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class AdminController extends Controller
{
    /**
     * Display first page after login (dashboard page)
     */
    public function home(Request $request)
    {
        return Inertia::render('Home');
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function dashboard(Request $request)
    {
        return Inertia::render('Admin/Home');
    }


    /**
     * Display first page after login (dashboard page)
     */
    public function users(Request $request)
    {
        $this->authorize('adminUpdate', Auth::user());

        $users = User::get();
        return Inertia::render('Admin/Home', ['results' => $users, 'page' => 'users']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function userEdit(Request $request, User $user): \Inertia\Response
    {
        return Inertia::render('Admin/Home', ['results' => $user, 'page' => 'user-edit']);
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
        $user->disabled = $request->disabled;
        $user->save();

        //reset roles
        $roles = Role::whereIn('name', [Role::YEAF_ADMIN, Role::YEAF_USER, Role::YEAF_GUEST])->get();
        foreach ($roles as $role) {
            $user->roles()->detach($role);
        }

        //if admin add admin role
        if ($request->access_type == 'A') {
            $role = Role::where('name', Role::YEAF_ADMIN)->first();
            $user->roles()->attach($role);
        } else {
            $role = Role::where('name', Role::YEAF_USER)->first();
            $user->roles()->attach($role);
        }

        return Redirect::route('yeaf.maintenance.staff.list');
    }

}

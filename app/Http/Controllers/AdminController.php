<?php

namespace App\Http\Controllers;

use App\Http\Requests\MinistryEditRequest;
use App\Models\Ministry;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{

    /**
     * Display first page after login (dashboard page)
     */
    public function dashboard(Request $request): Response {
        return Inertia::render('Admin/Home');
    }


    /**
     * Display first page after login (dashboard page)
     */
    public function users(Request $request): Response {
        $this->authorize('adminUpdate', Auth::user());

        $ministry = Ministry::first();
        $users = User::with('roles')->get();
        $roles = Role::orderBy('name')->get();
        return Inertia::render('Admin/Home', ['users' => $users, 'roles' => $roles, 'ministry' => $ministry,
            'page' => 'users']);
    }

    /**
     * Display a listing of the resource.
     */
    public function userEdit(Request $request, User $user): Response
    {
        $this->authorize('update', $user);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->tele = $request->input('tele');
        $user->disabled = $request->disabled;
        $user->save();

        //reset roles
        $user->roles()->detach();
        foreach ($request->updatedRoles as $role){
            $user->roles()->attach($role['id']);
        }

        $ministry = Ministry::first();
        $users = User::with('roles')->get();
        $roles = Role::orderBy('name')->get();

        return Inertia::render('Admin/Home', ['users' => $users, 'roles' => $roles, 'ministry' => $ministry,
            'page' => 'users']);
    }


    /**
     * Display first page after login (dashboard page)
     */
    public function ministry(Request $request): Response {
        $this->authorize('adminUpdate', Ministry::class);

        $ministry = Ministry::first();
        $users = User::with('roles')->get();
        $roles = Role::orderBy('name')->get();
        return Inertia::render('Admin/Home', ['users' => $users, 'roles' => $roles, 'ministry' => $ministry,
            'page' => 'ministry']);
    }


    /**
     * Display a listing of the resource.
     */
    public function ministryEdit(MinistryEditRequest $request, User $user): Response
    {
        $this->authorize('update', Ministry::class);
        Ministry::query()->update($request->validated());

        $ministry = Ministry::first();
        $users = User::with('roles')->get();
        $roles = Role::orderBy('name')->get();
        return Inertia::render('Admin/Home', ['users' => $users, 'roles' => $roles, 'ministry' => $ministry,
            'page' => 'ministry']);
    }

}

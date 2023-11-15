<?php

namespace Modules\Vss\Http\Controllers;

use App\Http\Requests\AjaxRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Response;

class AdminController extends Controller
{
    /**
     * fetch active support users
     */
    public function activeUsers(AjaxRequest $request)
    {
        $users = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER]);
        })->where('disabled', false)->get();

        return Response::json(['status' => true, 'users' => $users]);
    }

    /**
     * fetch cancelled support users
     */
    public function cancelledUsers(AjaxRequest $request)
    {
        $users = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER]);
        })->where('disabled', true)->get();

        return Response::json(['status' => true, 'users' => $users]);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function reports(Request $request)
    {
        return Inertia::render('Vss::Reports', ['results' => null]);
    }
}

<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\AjaxRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Response;

class AdminController extends Controller
{

    /**
     * fetch active support users
     *
     * @param AjaxRequest $request
     * @return JsonResponse
     */
    public function activeUsers(AjaxRequest $request)
    {
        $users = User::whereHas('roles', fn($q) => $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER]))->where('disabled', false)->get();

        return HttpResponse::json(['status' => true, 'users' => $users]);
    }

    /**
     * fetch cancelled support users
     *
     * @param AjaxRequest $request
     * @return JsonResponse
     */
    public function cancelledUsers(AjaxRequest $request)
    {
        $users = User::whereHas('roles', fn($q) => $q->whereIn('name', [Role::VSS_ADMIN, Role::VSS_USER]))->where('disabled', true)->get();

        return HttpResponse::json(['status' => true, 'users' => $users]);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function reports(Request $request): Response {
        return Inertia::render('Vss::Reports', ['results' => null]);
    }
}

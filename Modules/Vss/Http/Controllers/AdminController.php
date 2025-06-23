<?php

namespace Modules\Vss\Http\Controllers;

use App\Http\Requests\AjaxRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{

    /**
     * Display first page after login (dashboard page)
     */
    public function reports(Request $request): Response {
        return Inertia::render('Vss::Reports', ['results' => null]);
    }
}

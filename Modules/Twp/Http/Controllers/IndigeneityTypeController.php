<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Twp\Entities\IndigeneityType;
use Illuminate\Support\Facades\Redirect;

class IndigeneityTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize('viewAny', IndigeneityType::class);
        $indigeneityTypes = IndigeneityType::get();
        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $indigeneityTypes, 'page' => 'indigeneity']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Request $request
     * @param \Modules\Twp\Entities\IndigeneityType $indigeneity
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, IndigeneityType $indigeneity): RedirectResponse
    {
        $this->authorize('update', IndigeneityType::class);
        $indigeneity->title = $request->title;
        $indigeneity->active_flag = $request->active_flag;
        $indigeneity->save();

        return Redirect::route('twp.maintenance.indigeneity.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', IndigeneityType::class);
        IndigeneityType::create($request->all());
        return Redirect::route('twp.maintenance.indigeneity.index');
    }
}

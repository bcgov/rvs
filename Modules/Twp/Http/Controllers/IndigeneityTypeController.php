<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Twp\Entities\IndigeneityType;
use Illuminate\Support\Facades\Redirect;

class IndigeneityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('create', IndigeneityType::class);
        $indigeneityTypes = IndigeneityType::get();
        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $indigeneityTypes, 'page' => 'indigeneity']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  IndigeneityType  $indigeneityType
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function update(Request $request, IndigeneityType $indigeneity): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', IndigeneityType::class);
        $indigeneity->title = $request->title;
        $indigeneity->active_flag = $request->active_flag;
        $indigeneity->save();

        return Redirect::route('twp.maintenance.indigeneity.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('create', IndigeneityType::class);
        IndigeneityType::create($request->all());
        return Redirect::route('twp.maintenance.indigeneity.index');
    }
}

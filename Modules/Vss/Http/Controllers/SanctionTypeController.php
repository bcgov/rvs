<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Vss\Entities\SanctionType;
use Modules\Vss\Http\Requests\SanctionTypeStoreRequest;

class SanctionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $sanctions = SanctionType::orderBy('sanction_code', 'asc')->get();

        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $sanctions, 'page' => 'sanction-type']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SanctionTypeStoreRequest $request)
    {
        SanctionType::create($request->validated());

        return Redirect::route('vss.maintenance.sanction-type.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SanctionTypeStoreRequest $request, SanctionType $sanctionType)
    {
        SanctionType::where('id', $sanctionType->id)->update($request->validated());

        return Redirect::route('vss.maintenance.sanction-type.index');
    }
}

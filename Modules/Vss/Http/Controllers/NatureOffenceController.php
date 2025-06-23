<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Vss\Entities\NatureOffence;
use Modules\Vss\Http\Requests\NatureOffenceStoreRequest;

class NatureOffenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        $offences = NatureOffence::orderBy('nature_code', 'asc')->get();

        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $offences, 'page' => 'nature-offence']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NatureOffenceStoreRequest $request): RedirectResponse {
        NatureOffence::create($request->validated());

        return Redirect::route('vss.maintenance.nature-offence.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NatureOffenceStoreRequest $request, NatureOffence $natureOffence): RedirectResponse {
        //if the nature offence code updated
        if ($request->nature_code !== $natureOffence->nature_code) {
            //create new area
            $new_offence = NatureOffence::create($request->validated());

            //re-attach incidents from the old school to the new
            $natureOffence->offences()->update(['nature_code' => $new_offence->nature_code]);

            //delete old school
            $natureOffence->delete();
        } else {
            NatureOffence::where('id', $natureOffence->id)->update($request->validated());
        }

        return Redirect::route('vss.maintenance.nature-offence.index');
    }
}

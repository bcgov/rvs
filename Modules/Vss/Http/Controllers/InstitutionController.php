<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Vss\Entities\Institution;
use Modules\Vss\Http\Requests\InstitutionStoreRequest;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        $schools = Institution::orderBy('institution_code', 'asc')->get();

        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $schools, 'page' => 'school']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(InstitutionStoreRequest $request): RedirectResponse {
        Institution::create($request->validated());

        return Redirect::route('vss.maintenance.school.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(InstitutionStoreRequest $request, Institution $school): RedirectResponse {
        //if the school code updated
        if ($request->institution_code !== $school->institution_code) {
            //create new school
            $new_school = Institution::create($request->validated());

            //re-attach incidents from the old school to the new
            $school->incidents()->update(['institution_code' => $new_school->institution_code]);

            //delete old school
            $school->delete();
        } else {
            Institution::where('id', $school->id)->update($request->validated());
        }

        return Redirect::route('vss.maintenance.school.index');
    }
}

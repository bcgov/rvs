<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Vss\Entities\SanctionType;
use Modules\Vss\Http\Requests\SanctionTypeStoreRequest;

class SanctionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response {
        $sanctions = SanctionType::orderBy('sanction_code', 'asc')->get();

        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $sanctions, 'page' => 'sanction-type']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(SanctionTypeStoreRequest $request): RedirectResponse {
        SanctionType::create($request->validated());

        return Redirect::route('vss.maintenance.sanction-type.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     */
    public function update(SanctionTypeStoreRequest $request, SanctionType $sanctionType): RedirectResponse {
        SanctionType::where('id', $sanctionType->id)->update($request->validated());

        return Redirect::route('vss.maintenance.sanction-type.index');
    }
}

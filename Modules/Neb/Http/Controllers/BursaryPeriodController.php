<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Neb\Entities\Application;
use Modules\Neb\Entities\BursaryPeriod;
use Modules\Neb\Entities\Neb;
use Modules\Neb\Http\Requests\BursaryPeriodEditRequest;
use Modules\Neb\Http\Requests\BursaryPeriodStoreRequest;

class BursaryPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        return Inertia::render('Neb::BursaryPeriods', ['page' => 'bursary-periods']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function tobeAwarded(): JsonResponse {
        $bp = BursaryPeriod::where('awarded', false)->get();

        return \Illuminate\Support\Facades\Response::json([
            'status' => 'success',
            'bp' => $bp,
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return response.json
     */
    public function fetch(Request $request): Response {
        if ($request->id) {
            $bp = BursaryPeriod::find($request->id);

            return Response::json([
                'id' => $request->id,
                'page' => 'bursary-periods',
                'bp' => $bp,
            ]);
        }

        $bursaryPeriods = BursaryPeriod::orderBy('bursary_period_start_date', 'desc')->get();

        return Response::json([
            'page' => 'bursary-periods',
            'bp' => $bursaryPeriods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Neb\Http\Requests\BursaryPeriodStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BursaryPeriodStoreRequest $request): RedirectResponse {
        $bursaryPeriod = BursaryPeriod::create($request->validated());

        return Redirect::route('neb.bursary-periods.show', [$bursaryPeriod->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\Neb\Http\Requests\BursaryPeriodEditRequest $request
     * @param \Modules\Neb\Entities\BursaryPeriod $bursaryPeriod
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BursaryPeriodEditRequest $request, BursaryPeriod $bursaryPeriod): RedirectResponse {
        $bursaryPeriod::where('id', $bursaryPeriod->id)->update($request->validated());

        return Redirect::route('neb.bursary-periods.show', [$bursaryPeriod->id]);
    }

    /**
     * Delete a resource.
     *
     * @return \Illuminate\Http\RedirectResponse.redirect
     */
    public function delete(Request $request): RedirectResponse {
        $bP = BursaryPeriod::where('id', $request->input('id'))->first();
        if ($bP != null) {
            //remove all records entered for the same bursary period from previous runs
            Application::where('bursary_period_id', $bP->id)->delete();
            Neb::where('bursary_period_id', $bP->id)->delete();

            $bP->delete();
        }

        return Redirect::route('neb.bursary-periods.index');
    }
}

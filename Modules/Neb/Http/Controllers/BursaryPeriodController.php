<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Neb\Entities\Application;
use Modules\Neb\Entities\BursaryPeriod;
use Modules\Neb\Entities\Neb;
use Modules\Neb\Http\Requests\BursaryPeriodEditRequest;
use Modules\Neb\Http\Requests\BursaryPeriodStoreRequest;
use Response;

class BursaryPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Neb::BursaryPeriods', ['page' => 'bursary-periods']);
    }

    public function tobeAwarded()
    {
        $bp = BursaryPeriod::where('awarded', false)->get();

        return Response::json([
            'status' => 'success',
            'bp' => $bp,
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return response.json
     */
    public function fetch(Request $request)
    {
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

    public function show(Request $request)
    {
        return Inertia::render('Neb::BursaryPeriod', ['results' => null, 'stats' => null, 'id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BursaryPeriodStoreRequest $request)
    {
        $bursaryPeriod = BursaryPeriod::create($request->validated());

        return Redirect::route('neb.bursary-periods.show', [$bursaryPeriod->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BursaryPeriodEditRequest $request, BursaryPeriod $bursaryPeriod)
    {
        $bursaryPeriod::where('id', $bursaryPeriod->id)->update($request->validated());

        return Redirect::route('neb.bursary-periods.show', [$bursaryPeriod->id]);
    }

    /**
     * Delete a resource.
     *
     * @return \Illuminate\Http\RedirectResponse.redirect
     */
    public function delete(Request $request)
    {
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

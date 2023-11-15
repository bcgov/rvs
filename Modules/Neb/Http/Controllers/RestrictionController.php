<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Neb\Entities\Restriction;
use Modules\Neb\Http\Requests\RestrictionStoreRequest;
use Response;

class RestrictionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Neb::Restrictions', ['page' => 'restrictions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return response.json
     */
    public function fetch(\Illuminate\Http\Request $request)
    {
        if ($request->id) {
            $restriction = Restriction::find($request->id);

            return Response::json([
                'page' => 'restrictions',
                'restrictions' => $restriction,
            ]);
        }

        $restrictions = Restriction::orderBy('restriction_code', 'asc')->get();

        return Response::json([
            'page' => 'restrictions',
            'restrictions' => $restrictions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RestrictionStoreRequest $request)
    {
        $restriction = Restriction::create($request->validated());

        return Redirect::route('neb.restrictions.show', [$restriction->id]);
    }
}

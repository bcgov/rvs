<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Modules\Neb\Entities\Restriction;
use Modules\Neb\Http\Requests\RestrictionStoreRequest;


class RestrictionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        return Inertia::render('Neb::Restrictions', ['page' => 'restrictions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse .json
     */
    public function fetch(Request $request): JsonResponse {
        if ($request->id) {
            $restriction = Restriction::find($request->id);

            return FacadeResponse::json([
                'page' => 'restrictions',
                'restrictions' => $restriction,
            ]);
        }

        $restrictions = Restriction::orderBy('restriction_code', 'asc')->get();

        return FacadeResponse::json([
            'page' => 'restrictions',
            'restrictions' => $restrictions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RestrictionStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(RestrictionStoreRequest $request): RedirectResponse {
        $restriction = Restriction::create($request->validated());

        return Redirect::route('neb.restrictions.show', [$restriction->id]);
    }
}

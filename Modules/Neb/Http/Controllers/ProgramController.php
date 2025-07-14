<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Modules\Neb\Entities\Program;
use Modules\Neb\Http\Requests\ProgramStoreRequest;

class ProgramController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        return Inertia::render('Neb::NebPrograms', ['page' => 'programs']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse.json
     */
    public function fetch(Request $request): JsonResponse {
        if ($request->id) {
            $program = Program::find($request->id);

            return FacadeResponse::json([
                'id' => $request->id,
                'page' => 'programs',
                'programs' => $program,
            ]);
        }

        $nebPrograms = Program::orderBy('program_code', 'asc')->get();

        return FacadeResponse::json([
            'page' => 'programs',
            'programs' => $nebPrograms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Neb\Http\Requests\ProgramStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProgramStoreRequest $request): RedirectResponse {
        $program = Program::create($request->validated());

        return Redirect::route('neb.programs.show', [$program->id]);
    }
}

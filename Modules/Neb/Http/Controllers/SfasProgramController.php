<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Modules\Neb\Entities\Program;
use Modules\Neb\Entities\SfasProgram;
use Modules\Neb\Http\Requests\SfasProgramStoreRequest;

class SfasProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        return Inertia::render('Neb::SfasPrograms', ['page' => 'sfas-programs']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(\Illuminate\Http\Request $request): JsonResponse {
        if ($request->id) {
            $sfasProgram = SfasProgram::find($request->id);

            return FacadeResponse::json([
                'page' => 'sfas-programs',
                'sfas_programs' => $sfasProgram,
            ]);
        }

        $sfasPrograms = SfasProgram::orderBy('sfas_program_code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')->get();

        return FacadeResponse::json([
            'page' => 'sfas-programs',
            'sfas_programs' => $sfasPrograms,
            'programs' => $programs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Neb\Http\Requests\SfasProgramStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SfasProgramStoreRequest $request): RedirectResponse {
        $sfasProgram = SfasProgram::create($request->validated());

        return Redirect::route('neb.sfas-programs.show', [$sfasProgram->id]);
    }
}

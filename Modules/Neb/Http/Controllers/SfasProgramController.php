<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Neb\Entities\Program;
use Modules\Neb\Entities\SfasProgram;
use Modules\Neb\Http\Requests\SfasProgramStoreRequest;
use Response;

class SfasProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Neb::SfasPrograms', ['page' => 'sfas-programs']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return response.json
     */
    public function fetch(\Illuminate\Http\Request $request)
    {
        if ($request->id) {
            $sfasProgram = SfasProgram::find($request->id);

            return Response::json([
                'page' => 'sfas-programs',
                'sfas_programs' => $sfasProgram,
            ]);
        }

        $sfasPrograms = SfasProgram::orderBy('sfas_program_code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')->get();

        return Response::json([
            'page' => 'sfas-programs',
            'sfas_programs' => $sfasPrograms,
            'programs' => $programs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SfasProgramStoreRequest $request)
    {
        $sfasProgram = SfasProgram::create($request->validated());

        return Redirect::route('neb.sfas-programs.show', [$sfasProgram->id]);
    }
}

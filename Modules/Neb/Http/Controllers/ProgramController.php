<?php

namespace Modules\Neb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Neb\Entities\Program;
use Modules\Neb\Http\Requests\ProgramStoreRequest;
use Response;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Neb::NebPrograms', ['page' => 'programs']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return response.json
     */
    public function fetch(Request $request)
    {
        if ($request->id) {
            $program = Program::find($request->id);

            return Response::json([
                'id' => $request->id,
                'page' => 'programs',
                'programs' => $program,
            ]);
        }

        $nebPrograms = Program::orderBy('program_code', 'asc')->get();

        return Response::json([
            'page' => 'programs',
            'programs' => $nebPrograms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProgramStoreRequest $request)
    {
        $program = Program::create($request->validated());

        return Redirect::route('neb.programs.show', [$program->id]);
    }
}

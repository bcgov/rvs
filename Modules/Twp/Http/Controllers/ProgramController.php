<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Twp\Entities\Program;
use Modules\Twp\Entities\ProgramHistory;
use Modules\Twp\Http\Requests\ProgramEditRequest;
use Modules\Twp\Http\Requests\ProgramStoreRequest;

class ProgramController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(ProgramStoreRequest $request)
    {
        $application = Program::create($request->validated());

        return Redirect::route('twp.students.show', [$application->student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     */
    public function update(ProgramEditRequest $request, Program $program)
    {
        $history = $program->toArray();
        $history['program_twp_id'] = $program->id;
        ProgramHistory::create($history);

        Program::where('id', $program->id)->update($request->validated());
        $program = Program::find($program->id);

        return Redirect::route('twp.students.show', [$program->student_id]);
    }
}

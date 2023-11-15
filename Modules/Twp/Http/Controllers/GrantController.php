<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Modules\Twp\Entities\Grant;
use Modules\Twp\Http\Requests\GrantEditRequest;
use Modules\Twp\Http\Requests\GrantStoreRequest;

class GrantController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GrantStoreRequest $request)
    {
        $grant = Grant::create($request->validated());

        return Redirect::route('twp.students.show', [$grant->student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GrantEditRequest $request, Grant $grant)
    {
        Grant::where('id', $grant->id)->update($request->validated());
        $grant = Grant::find($grant->id);

        return Redirect::route('twp.students.show', [$grant->student_id]);
    }
}

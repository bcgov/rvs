<?php

namespace Modules\Plsc\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Plsc\Entities\Application;
use Modules\Plsc\Http\Requests\ApplicationEditRequest;
use Modules\Plsc\Http\Requests\ApplicationStoreRequest;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('plsc::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('plsc::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ApplicationStoreRequest $request)
    {
        $application = Application::create($request->validated());

        return Redirect::route('plsc.students.show', [$application->student->id]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('plsc::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('plsc::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ApplicationEditRequest $request, Application $application)
    {
        Application::where('id', $application->id)->update($request->validated());

        return Redirect::route('plsc.students.show', [$application->student->id]);
//
//
//        Application::where('id', $application->id)->update($request->validated());
//        $application = Application::find($application->id);
//
//        return Redirect::route('twp.students.show', [$application->student_id]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

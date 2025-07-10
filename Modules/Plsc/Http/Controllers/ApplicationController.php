<?php

namespace Modules\Plsc\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
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
    public function index(): Renderable {
        return view('plsc::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(): Renderable {
        return view('plsc::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Plsc\Http\Requests\ApplicationStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ApplicationStoreRequest $request): RedirectResponse {
        $application = Application::create($request->validated());

        return Redirect::route('plsc.students.show', [$application->student->id]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): Renderable {
        return view('plsc::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id): Renderable {
        return view('plsc::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\Plsc\Http\Requests\ApplicationEditRequest $request
     * @param \Modules\Plsc\Entities\Application $application
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ApplicationEditRequest $request, Application $application): RedirectResponse {
        Application::where('id', $application->id)->update($request->validated());

        return Redirect::route('plsc.students.show', [$application->student->id]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return void
     */
    public function destroy($id): void
    {
        //
    }
}

<?php

namespace Modules\Yeaf\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Yeaf\Entities\Country;
use Modules\Yeaf\Entities\Institution;
use Modules\Yeaf\Entities\Province;
use Modules\Yeaf\Http\Requests\InstitutionStoreRequest;
use Modules\Yeaf\Http\Requests\InstitutionUpdateRequest;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $schools = new Institution();
        $schools = $this->paginateSchools($schools);
        [$countries, $provinces] = $this->getCountriesProvinces();
        $filter_name = $request->input('filter_name');

        return Inertia::render('Yeaf::Schools', ['status' => true, 'filterName' => $filter_name, 'results' => $schools, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Inertia\Response
     */
    public function store(InstitutionStoreRequest $request)
    {
        $institution = Institution::create($request->validated());
        $schools = new Institution();
        $schools = $this->paginateSchools($schools);

        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf::Schools', ['status' => true, 'filterName' => '', 'school' => $institution, 'results' => $schools, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Inertia\Response
     */
    public function show(Institution $institution)
    {
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf::SchoolEdit', ['status' => true, 'result' => $institution, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Inertia\Response
     */
    public function update(InstitutionUpdateRequest $request, Institution $institution)
    {
        Institution::where('id', $institution->id)->update($request->validated());
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf::SchoolEdit', ['status' => true, 'result' => $institution, 'countries' => $countries, 'provinces' => $provinces]);
    }

    private function paginateSchools($schools)
    {
        if (request()->filter_name !== null) {
            $schools = $schools->where('name', 'ILIKE', '%'.request()->filter_name.'%');
        }

        if (request()->filter_city !== null) {
            $schools = $schools->where('city', 'ILIKE', request()->filter_city);
        }

        if (request()->sort !== null) {
            $schools = $schools->orderBy(request()->sort, request()->direction);
        } else {
            $schools = $schools->orderBy('created_at', 'desc');
        }

        return $schools->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    private function getCountriesProvinces()
    {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}

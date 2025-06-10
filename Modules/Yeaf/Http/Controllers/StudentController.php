<?php

namespace Modules\Yeaf\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Yeaf\Entities\Batch;
use Modules\Yeaf\Entities\Country;
use Modules\Yeaf\Entities\Ineligible;
use Modules\Yeaf\Entities\Institution;
use Modules\Yeaf\Entities\Program;
use Modules\Yeaf\Entities\ProgramYear;
use Modules\Yeaf\Entities\Province;
use Modules\Yeaf\Entities\Student;
use Modules\Yeaf\Http\Requests\StudentStoreRequest;
use Modules\Yeaf\Http\Requests\StudentUpdateRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        $students = $this->paginateStudents();
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf::Students', ['status' => true, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Inertia\Response
     */
    public function store(StudentStoreRequest $request): Response {
        $student = Student::create($request->validated());
        $students = $this->paginateStudents();
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf::Students', ['status' => true, 'student' => $student, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Inertia\Response
     */
    public function show(Student $student): Response {
        $student = Student::where('id', $student->id)->with('grants.school', 'grants.grantPendingIneligibles', 'grants.grantDeniedIneligibles', 'grants.appeals', 'comments')->first();
        $countries = Country::orderBy('country_code', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        $program_types = Program::orderBy('program_description', 'asc')->get();
        $program_years = ProgramYear::orderBy('year_start', 'desc')->get();
        $schools = Institution::orderBy('name', 'asc')->get();
        $batches = Batch::orderBy('batch_number', 'desc')->get();
        $active_staff = User::where('disabled', 'false')->orderBy('user_id', 'asc')->get();
        $all_staff = User::orderBy('user_id', 'asc')->get();
        $ineligibles = Ineligible::orderBy('description')->get();

        return Inertia::render('Yeaf::StudentEdit', ['status' => true,
            'program_types' => $program_types,
            'program_years' => $program_years,
            'schools' => $schools,
            'batches' => $batches,
            'ineligibles' => $ineligibles,
            'active_staff' => $active_staff,
            'all_staff' => $all_staff,
            'result' => $student, 'countries' => $countries, 'provinces' => $provinces, ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student): RedirectResponse {
        Student::where('id', $student->id)->update($request->validated());

        return Redirect::route('yeaf.students.show', [$student->id]);
    }

    private function paginateStudents(): LengthAwarePaginator
    {
        if (request()->sort !== null) {
            $students = Student::orderBy(request()->sort, request()->direction);
        } else {
            $students = Student::orderBy('created_at', 'desc');
        }
        if (request()->filter_sin !== null) {
            $students = $students->where('sin', request()->filter_sin);
        }

        if (request()->filter_fname !== null) {
            $students = $students->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $students = $students->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }

        return $students->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    /**
     * @return array<int, Collection>
     */
    private function getCountriesProvinces(): array {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}

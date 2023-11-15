<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Twp\Entities\Application;
use Modules\Twp\Entities\Institution;
use Modules\Twp\Entities\PaymentType;
use Modules\Twp\Entities\Reason;
use Modules\Twp\Entities\Student;
use Modules\Twp\Http\Requests\StudentStoreRequest;
use Modules\Twp\Http\Requests\StudentUpdateRequest;
use Modules\Yeaf\Entities\Country;
use Modules\Yeaf\Entities\Province;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $schools = Institution::orderBy('name', 'asc')->get();
        $students = new Student();
        $students = $this->paginateStudents($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Twp::Students', ['status' => true, 'schools' => $schools, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function apps()
    {
        $schools = Institution::orderBy('name', 'asc')->get();
        $apps = new Application();
        $apps = $this->paginateApps($apps);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Twp::Applications', ['status' => true, 'schools' => $schools, 'results' => $apps, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Inertia\Response
     */
    public function store(StudentStoreRequest $request)
    {
        $schools = Institution::orderBy('name', 'asc')->get();
        $student = Student::create($request->validated());
        $students = new Student();
        $students = $this->paginateStudents($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Twp::Students', ['status' => true, 'schools' => $schools, 'student' => $student, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Inertia\Response
     */
    public function show(Student $student)
    {
        $student = Student::where('id', $student->id)
            ->with('applications.reasons', 'applications.program.versions', 'applications.program.institution', 'applications.payments', 'applications.grants')
            ->first();
        $reasons = Reason::orderBy('reason_status', 'asc')->get();
        $schools = Institution::orderBy('name', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();
        $p_types = PaymentType::where('active_flag', true)->orderBy('title', 'asc')->get();

        return Inertia::render('Twp::StudentEdit', ['status' => true, 'schools' => $schools, 'p_types' => $p_types,
            'result' => $student, 'reasons' => $reasons, 'provinces' => $provinces, ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        Student::where('id', $student->id)->update($request->validated());
        $student = Student::find($student->id);

        return Redirect::route('twp.students.show', [$student->id]);
    }

    private function paginateStudents($students)
    {
        $students = $students->with('applications');

        if (request()->sort === 'app_status' && request()->direction !== 'ALL') {
            $students = $students->whereHas('applications', function ($q) {
                $q->where('application_status', request()->direction);
            });
        }

        if (request()->filter_fname !== null) {
            $students = $students->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $students = $students->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }

        if (request()->filter_school !== null) {
            $students = $students->whereHas('applications', function ($query) {
                $query->whereHas('program', function ($q) {
                    $q->where('institution_twp_id', request()->filter_school);
                });
            });
        }

        if (request()->sort !== null && request()->sort !== 'app_status') {
            $students = $students->orderBy(request()->sort, request()->direction);
        } else {
            $students = $students->orderBy('created_at', 'desc');
        }

        return $students->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    private function paginateApps($apps)
    {
        $apps = $apps->with('student');

        if (request()->sort === 'app_status' && request()->direction !== 'ALL') {
            $apps = $apps->where('application_status', request()->direction);
        }

        if (request()->filter_fname !== null) {
            $apps = $apps->whereHas('student', function ($q) {
                $q->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
            });
        }
        if (request()->filter_lname !== null) {
            $apps = $apps->whereHas('student', function ($q) {
                $q->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
            });
        }

        if (request()->filter_school !== null) {
            $apps = $apps->whereHas('program', function ($q) {
                $q->where('institution_twp_id', request()->filter_school);
            });
        }

        if (request()->sort !== null && request()->sort !== 'app_status') {
            $apps = $apps->orderBy(request()->sort, request()->direction);
        } else {
            $apps = $apps->orderBy('created_at', 'desc');
        }

        return $apps->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    private function getCountriesProvinces()
    {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}

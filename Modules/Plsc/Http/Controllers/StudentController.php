<?php

namespace Modules\Plsc\Http\Controllers;

use Modules\Plsc\Entities\Application;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Plsc\Entities\Institution;
use Modules\Plsc\Entities\Student;
use Modules\Plsc\Http\Requests\StudentStoreRequest;
use Modules\Plsc\Http\Requests\StudentUpdateRequest;
use Modules\Yeaf\Entities\Country;
use Modules\Yeaf\Entities\Province;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): Response {
        $schools = Institution::orderBy('name', 'asc')->get();
        $students = $this->paginateStudents();
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Plsc::Students', [
            'status' => true,
            'schools' => $schools,
            'results' => $students,
            'countries' => $countries,
            'provinces' => $provinces,
        ]);
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
     * @param StudentStoreRequest $request
     *
     * @return Response
     */
    public function store(StudentStoreRequest $request): Response {
        $schools = Institution::orderBy('name', 'asc')->get();
        $student = Student::create($request->validated());
        $students = $this->paginateStudents();
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Plsc::Students', [
            'status' => true,
            'schools' => $schools,
            'student' => $student,
            'results' => $students,
            'countries' => $countries,
            'provinces' => $provinces,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param Student $student
     *
     * @return Response
     */
    public function show(Student $student): Response {
        $student = Student::where('id', $student->id)
            ->with(
                'applications')
            ->first();
        $schools = Institution::orderBy('name', 'asc')->get();
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Plsc::StudentEdit', ['status' => true, 'schools' => $schools,
            'result' => $student, 'provinces' => $provinces, 'countries' => $countries,]);
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
     * @param StudentUpdateRequest $request
     * @param Student $student
     *
     * @return RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student): RedirectResponse {
        $studentRecord = Student::where('id', $student->id)->first();
        $studentRecord->update($request->validated());

        return Redirect::route('plsc.students.show', [$student->id]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     *
     */
    public function destroy($id): void {
        //
    }


    /**
     * @return LengthAwarePaginator<Student>
     */
    private function paginateStudents(): LengthAwarePaginator {
        $students = Student::with('applications');

        if (request()->sort === 'app_status' && request()->direction !== 'ALL') {
            $students = $students->whereHas('applications', function ($q): void {
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
            $students = $students->whereHas('applications', function ($query): void {
                $query->whereHas('program', function ($q): void {
                    $q->where('institution_id', request()->filter_school);
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

    /**
     * @param Builder<Application> $apps
     *
     * @return mixed
     */
    private function paginateApps(Builder $apps): mixed {
        $apps = $apps->with('student');

        if (request()->sort === 'app_status' && request()->direction !== 'ALL') {
            $apps = $apps->where('application_status', request()->direction);
        }

        if (request()->filter_fname !== null) {
            $apps = $apps->whereHas('student', function ($q): void {
                $q->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
            });
        }
        if (request()->filter_lname !== null) {
            $apps = $apps->whereHas('student', function ($q): void {
                $q->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
            });
        }

        if (request()->filter_school !== null) {
            $apps = $apps->where('institution_id', request()->filter_school);
        }

        if (request()->sort !== null && request()->sort !== 'app_status') {
            $apps = $apps->leftJoin('students', 'applications.student_id', '=', 'students.id')
                ->select('applications.*')
                ->addSelect("students.".request()->sort." as student_".request()->sort)
                ->orderBy("student_".request()->sort, request()->direction);
        } else {
            $apps = $apps->orderBy('created_at', 'desc');
        }

        return $apps->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    /**
     * @return array{0: Collection<int, Country>, 1: Collection<int, Province>}
     */
    private function getCountriesProvinces(): array {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}

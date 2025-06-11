<?php

namespace Modules\Yeaf\Http\Controllers;

use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Yeaf\Entities\Admin;
use Modules\Yeaf\Entities\Batch;
use Modules\Yeaf\Entities\Ineligible;
use Modules\Yeaf\Entities\ProgramYear;
use Modules\Yeaf\Entities\Province;
use Modules\Yeaf\Http\Requests\BatchEditRequest;
use Modules\Yeaf\Http\Requests\BatchStoreRequest;
use Modules\Yeaf\Http\Requests\IneligibleEditRequest;
use Modules\Yeaf\Http\Requests\IneligibleStoreRequest;
use Modules\Yeaf\Http\Requests\MinistryEditRequest;
use Modules\Yeaf\Http\Requests\ProgramYearEditRequest;
use Modules\Yeaf\Http\Requests\ProgramYearStoreRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function letters(Request $request): Response
    {
        return Inertia::render('Yeaf::Maintenance', ['status' => true,
            'program_years' => ProgramYear::orderBy('year_start', 'desc')->get(),
            'batches' => Batch::orderBy('batch_number', 'desc')->get(), 'page' => 'letters', ]);
    }

    public function downloadLetter(Request $request, string $type, string|int $extra): BinaryFileResponse
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $now_t = date('H:m:i');
        $user = Auth::user();
        $file_name = '';
        if ($type === 'py') {
            $program_year = ProgramYear::find($extra);
            $pdf = PDF::loadView('yeaf::py-pdf', compact('program_year', 'admin', 'user', 'now_d', 'now_t'));
            $file_name = $program_year->year_start.'_'.$program_year->year_end;
        } elseif ($type === 'batch') {
            $batch = Batch::find($extra);
            $pdf = PDF::loadView('yeaf::batch-pdf', compact('batch', 'admin', 'user', 'now_d', 'now_t'));
            $file_name = $batch->batch_date;
        }
        else {
            abort(400, 'Invalid type specified');
        }

        return $pdf->download(mt_rand().'-'.$file_name.'-letter.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function staffList(Request $request): Response
    {
        $staff = User::with('roles')
            ->whereHas('roles', function ($q) {
                return $q->whereIn('name', [Role::YEAF_ADMIN, Role::YEAF_USER, Role::YEAF_GUEST]);
            })->orderBy('created_at', 'desc')->get();

        foreach ($staff as $user) {
            if ($user->roles->contains('name', Role::YEAF_ADMIN)) {
                $user->access_type = 'A';
            } else {
                $user->access_type = 'U';
            }
        }

        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function staffShow(Request $request, User $user): Response
    {
        if ($user->roles->contains('name', Role::YEAF_ADMIN)) {
            $user->access_type = 'A';
        } else {
            $user->access_type = 'U';
        }

        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function staffEdit(StaffEditRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $user->tele = $request->input('tele');
        $user->disabled = $request->disabled;
        $user->save();

        //reset roles
        $roles = Role::whereIn('name', [Role::YEAF_ADMIN, Role::YEAF_USER, Role::YEAF_GUEST])->get();
        foreach ($roles as $role) {
            $user->roles()->detach($role);
        }

        //if admin add admin role
        if ($request->access_type == 'A') {
            $role = Role::where('name', Role::YEAF_ADMIN)->first();
            $user->roles()->attach($role);
        } else {
            $role = Role::where('name', Role::YEAF_USER)->first();
            $user->roles()->attach($role);
        }

        return Redirect::route('yeaf.maintenance.staff.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function ineligiblesList(Request $request): Response
    {
        $ineligibles = Ineligible::orderBy('code_id', 'asc')->get();

        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'results' => $ineligibles, 'page' => 'ineligibles']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ineligibleEdit(IneligibleEditRequest $request, Ineligible $ineligible): RedirectResponse
    {
        $this->authorize('update', Ineligible::class);
        $ineligible->code_id = $request->code_id;
        $ineligible->description = $request->input('description');
        $ineligible->active_flag = $request->active_flag;
        $ineligible->code_type = $request->code_type;
        $ineligible->paragraph_text = $request->input('paragraph_text');
        $ineligible->save();

        return Redirect::route('yeaf.maintenance.ineligibles.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ineligibleStore(IneligibleStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Ineligible::class);
        $ineligible = Ineligible::create($request->validated());

        return Redirect::route('yeaf.maintenance.ineligibles.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function programYearsList(Request $request): Response
    {
        $program_years = ProgramYear::orderBy('year_start', 'desc')->get();

        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'results' => $program_years, 'page' => 'program-years']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function programYearEdit(ProgramYearEditRequest $request, ProgramYear $programYear): RedirectResponse
    {
        $this->authorize('update', ProgramYear::class);
        ProgramYear::where('id', $programYear->id)->update($request->validated());

        return Redirect::route('yeaf.maintenance.program-years.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function programYearStore(ProgramYearStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', ProgramYear::class);
        $py = ProgramYear::create($request->validated());

        return Redirect::route('yeaf.maintenance.program-years.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function batchesList(Request $request): Response
    {
        $batches = Batch::orderBy('batch_number', 'desc')->get();

        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'results' => $batches, 'page' => 'batches']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchEdit(BatchEditRequest $request, Batch $batch): RedirectResponse
    {
        $this->authorize('update', Batch::class);
        Batch::where('id', $batch->id)->update($request->validated());

        return Redirect::route('yeaf.maintenance.batches.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchStore(BatchStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Batch::class);
        Batch::create($request->validated());

        return Redirect::route('yeaf.maintenance.batches.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function ministryShow(Request $request): Response
    {
        $admin = Admin::first();
        $provinces = Province::where('country_code', 'CAN')->select('province_code')->get();

        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'results' => $admin, 'provinces' => $provinces, 'page' => 'ministry']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ministryUpdate(MinistryEditRequest $request, Admin $admin): RedirectResponse
    {
        $this->authorize('update', Admin::class);
        Admin::where('id', $admin->id)->update($request->validated());

        return Redirect::route('yeaf.maintenance.ministry.show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function reportsShow(Request $request): Response
    {
        return Inertia::render('Yeaf::Maintenance', ['status' => true, 'page' => 'reports']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response
    {
        return Inertia::render('Yeaf::Students');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function goToPage(Request $request, string $page = 'area-of-audit'): Response
    {
        return Inertia::render('Yeaf::Maintenance', ['page' => $page]);
    }
}

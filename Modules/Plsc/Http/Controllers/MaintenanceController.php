<?php

namespace Modules\Plsc\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Twp\Entities\Institution;
use Modules\Twp\Entities\PaymentType;
use Modules\Twp\Entities\Reason;
use Modules\Twp\Http\Controllers\Controller;
use Modules\Twp\Http\Requests\InstitutionEditRequest;
use Modules\Twp\Http\Requests\InstitutionStoreRequest;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function institutionList(Request $request): Response
    {
        $schools = Institution::orderBy('created_at', 'desc')->get();

        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $schools, 'page' => 'institutions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param InstitutionStoreRequest $request
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function institutionStore(InstitutionStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Institution::class);
        Institution::create($request->all());

        return Redirect::route('twp.maintenance.institutions.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param InstitutionEditRequest $request
     * @param Institution $institution
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function institutionUpdate(InstitutionEditRequest $request, Institution $institution): RedirectResponse
    {
        $this->authorize('update', $institution);
        Institution::where('id', $institution->id)->update($request->validated());

        return Redirect::route('twp.maintenance.institutions.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function staffList(Request $request): Response
    {
        $staff = User::with('roles')
            ->whereHas('roles', fn($q) => $q->whereIn('name', [Role::TWP_ADMIN, Role::TWP_USER, Role::TWP_GUEST]))->orderBy('created_at', 'desc')->get();

        foreach ($staff as $user) {
            if ($user->roles->contains('name', Role::TWP_ADMIN)) {
                $user->access_type = 'A';
            } else {
                $user->access_type = 'U';
            }
        }

        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function staffShow(Request $request, User $user): Response
    {
        if ($user->roles->contains('name', Role::TWP_ADMIN)) {
            $user->access_type = 'A';
        } else {
            $user->access_type = 'U';
        }

        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param StaffEditRequest $request
     * @param User $user
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function staffEdit(StaffEditRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $user->tele = $request->input('tele');
        //        $user->access_type = $request->access_type;
        $user->disabled = $request->disabled;
        $user->save();

        //reset roles
        $roles = Role::whereIn('name', [Role::TWP_ADMIN, Role::TWP_USER, Role::TWP_GUEST])->get();
        foreach ($roles as $role) {
            $user->roles()->detach($role);
        }

        //add user role

        //if admin add admin role
        if ($request->access_type == 'A') {
            $role = Role::where('name', Role::TWP_ADMIN)->first();
            $user->roles()->attach($role);
        } else {
            $role = Role::where('name', Role::TWP_USER)->first();
            $user->roles()->attach($role);
        }

        return Redirect::route('twp.maintenance.staff.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reasonList(Request $request): Response
    {
        $reasons = Reason::get();

        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $reasons, 'page' => 'reasons']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Request $request
     * @param Reason $reason
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function reasonUpdate(Request $request, Reason $reason): RedirectResponse
    {
        $this->authorize('update', Reason::class);
        $reason->reason_status = $request->reason_status;
        $reason->title = $request->title;
        $reason->letter_body = $request->letter_body;
        $reason->active_flag = $request->active_flag;
        $reason->save();

        return Redirect::route('twp.maintenance.reasons.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function reasonStore(Request $request): RedirectResponse
    {
        $this->authorize('create', Reason::class);
        Reason::create($request->all());

        return Redirect::route('twp.maintenance.reasons.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function paymentList(Request $request): Response
    {
        $this->authorize('create', PaymentType::class);
        $paymentTypes = PaymentType::get();

        return Inertia::render('Twp::Maintenance', ['status' => true, 'results' => $paymentTypes, 'page' => 'payments']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Request $request
     * @param PaymentType $payment
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function paymentUpdate(Request $request, PaymentType $payment): RedirectResponse
    {
        $this->authorize('update', PaymentType::class);
        $payment->title = $request->title;
        $payment->active_flag = $request->active_flag;
        $payment->save();

        return Redirect::route('twp.maintenance.payments.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function paymentStore(Request $request): RedirectResponse
    {
        PaymentType::create($request->all());

        return Redirect::route('twp.maintenance.payments.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string $page
     *
     * @return Response
     */
    public function goToPage(Request $request, string $page = 'staff'): Response
    {
        return Inertia::render('Twp::Maintenance', ['page' => $page]);
    }
}

<?php

namespace Modules\Vss\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Vss\Entities\ReferralSource;
use Modules\Vss\Http\Requests\ReferralSourceStoreRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReferralSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response {
        $referrals = ReferralSource::orderBy('referral_code', 'asc')->get();

        return Inertia::render('Vss::Maintenance', ['status' => true, 'results' => $referrals, 'page' => 'referral-source']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReferralSourceStoreRequest $request): RedirectResponse {
        ReferralSource::create($request->validated());

        return Redirect::route('vss.maintenance.referral-source.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReferralSourceStoreRequest $request, ReferralSource $referralSource): RedirectResponse {
        ReferralSource::where('id', $referralSource->id)->update($request->validated());

        return Redirect::route('vss.maintenance.referral-source.index');
    }
}

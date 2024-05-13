<?php

namespace Modules\Lfp\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Modules\Lfp\Entities\Lfp;
use Modules\Lfp\Entities\Payment;
use Modules\Lfp\Http\Requests\PaymentStoreRequest;
use Modules\Lfp\Http\Requests\PaymentEditRequest;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index($status = true, $newApp = 0)
    {
        $payments = $this->paginatePayments();

        return Inertia::render('Lfp::Payments', ['page' => 'applications', 'status' => $status, 'results' => $payments, 'app' => $newApp]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PaymentStoreRequest $request)
    {
        $payment = Payment::create($request->validated());

        return Redirect::route('lfp.applications.show', [$payment->lfp_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PaymentEditRequest $request, Payment $payment)
    {
        Payment::where('id', $payment->id)->update($request->validated());
        $payment = Payment::find($payment->id);

        return Redirect::route('lfp.applications.show', [$payment->lfp_id]);
    }

    private function paginatePayments()
    {
        $currentMonth = Carbon::now()->format('Y-m') . "-01";
        $lastMonth = Carbon::now()->subMonth()->format('Y-m') . "-01";
        $monthBeforeLast = Carbon::now()->subMonths(2)->format('Y-m') . "-01";

        $payments = Payment::whereIn('anniversary_date', [$currentMonth, $lastMonth, $monthBeforeLast])
            ->orderByDesc('anniversary_date')
            ->with('lfp');
        if (request()->filter_status !== null && request()->filter_status != 'all') {
            $payments = $payments->where('sfas_payment_status', request()->filter_status);
        }

        $payments = $payments->paginate(25)->onEachSide(1)->appends(request()->query());

        // inject payment data from sfas
        $payIds = $payments->pluck('pay_idx');

        // fetch sfas payments data in a single batch if payIds are not empty
        $sfasPays = [];
        if(!empty($payIds)) {
            $rawSfasPays = (new Payment)->sfasPayment($payIds->toArray());
            // convert list to a map for quick lookup
            $sfasPays = collect($rawSfasPays)->keyBy('pl_loan_forgiveness_pay_idx');
        }

        // inject sfas payment data into payments
        foreach ($payments as $payment) {
            $payment->sfas_payment = $sfasPays[$payment->pay_idx] ?? null;
        }

        // fetch lfps in a single batch query
        $appIds = $payments->pluck('app_idx')->unique();
        $lfps = Lfp::whereIn('app_idx', $appIds)->get()->keyBy('app_idx');

        // fetch individual data from sfas in a single batch if applicable
        $sins = $lfps->pluck('sin')->unique()->toArray();
        $sfasInds = !empty($sins) ? (new Lfp)->sfasInd($sins) : [];
        $sfasIndMap = collect($sfasInds)->keyBy('sin');

        // append to lfps with sfas individual data
        foreach ($lfps as $lfp) {
            $lfp->sfas_ind = $sfasIndMap[$lfp->sin] ?? null;
        }

        // inject sfas individual data into payments
        foreach ($payments as $payment) {
            $payment->sfas_ind = $lfps[$payment->app_idx]->sfas_ind ?? null;
        }

        return $payments;
    }
}

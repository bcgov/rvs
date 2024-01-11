<?php

namespace Modules\Lfp\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
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
        $payments = new Payment();
        $payments = $this->paginatePayments($payments);

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
        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');
        $monthBeforeLast = Carbon::now()->subMonths(2)->format('Y-m');
        $qry = sprintf(env("LFP_PAYMENTS_NO_FILTER"), $currentMonth, $lastMonth, $monthBeforeLast);

        if (request()->filter_status !== null && request()->filter_status != 'all') {
            $qry = sprintf(env("LFP_PAYMENTS_FILTER"), request()->filter_status, $currentMonth, $lastMonth, $monthBeforeLast);
        }
        $sfas = DB::connection('oracle')->select($qry . " ORDER BY pl_anniversary_dte DESC");
        $sfas = collect($sfas)->pluck('pl_loan_forgiveness_pay_idx');

        //start code to keep the order by the list as it came back from the external db
        $sfas_array = $sfas->toArray();
        $subquery = collect($sfas_array)
            ->map(function ($id) use ($sfas_array){
                return "WHEN $id THEN " . array_search($id, $sfas_array);
            })
            ->implode(' ');
        $payments = Payment::with('lfp')->whereIn('pay_idx', $sfas)
            ->orderByRaw("CASE pay_idx $subquery END");


        if (request()->filter_status == null || request()->filter_status == 'all') {
            $payments = $payments->orderBy('created_at', 'desc');
        }

        return $payments->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}

<?php

namespace Modules\Lfp\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Modules\Lfp\Entities\Payment;
use Modules\Lfp\Http\Requests\PaymentStoreRequest;
use Modules\Lfp\Http\Requests\PaymentEditRequest;

class PaymentController extends Controller
{
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
}

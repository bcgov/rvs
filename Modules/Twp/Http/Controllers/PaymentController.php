<?php

namespace Modules\Twp\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Modules\Twp\Entities\Payment;
use Modules\Twp\Http\Requests\PaymentEditRequest;
use Modules\Twp\Http\Requests\PaymentStoreRequest;

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

        return Redirect::route('twp.students.show', [$payment->student_id]);
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

        return Redirect::route('twp.students.show', [$payment->student_id]);
    }
}

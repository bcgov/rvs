<?php

namespace Modules\Lfp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lfp_id.*' => 'LFP ID field is not valid.',
            'payment_date.*' => 'Payment Date field is not valid.',
            'direct_lend_payment_amount.*' => 'Direct Lend Payment Amount field is not valid.',
            'direct_lend_interest_payment_amount.*' => 'Direct Lend Interest Payment Amount field is not valid.',
            'risk_sharing_payment_amount.*' => 'Risk Sharing Payment Amount field is not valid.',
            'risk_sharing_interest_payment_amount.*' => 'Risk Sharing Interest Payment Amount field is not valid.',
            'guaranteed_payment_amount.*' => 'Guaranteed Payment Amount field is not valid.',

            'entered_in_sfas_date.*' => 'Entered in SFAS Date field is not valid.',
            'entered_in_ici_date.*' => 'Entered in ICI Date field is not valid.',
            'reconciled_with_payment_report_date.*' => 'Reconciled with Payment Report Date field is not valid.',
            'reconciled_with_galaxy_date.*' => 'Reconciled with Galaxy Date field is not valid.',
            'comment.*' => 'Comment field is not valid.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'lfp_id' => 'required|exists:Modules\Lfp\Entities\Lfp,id',
            'payment_date' => 'required|date_format:Y-m-d',
            'direct_lend_payment_amount' => 'nullable|numeric',
            'direct_lend_interest_payment_amount' => 'nullable|numeric',
            'risk_sharing_payment_amount' => 'nullable|numeric',
            'risk_sharing_interest_payment_amount' => 'nullable|numeric',
            'guaranteed_payment_amount' => 'nullable|numeric',
            'amount_issued' => 'nullable|numeric',
            'reported_hours' => 'nullable|numeric',
            'employment_letter_provided' => 'nullable|boolean',
            'anniversary_date' => 'nullable|date_format:Y-m-d',
            'entered_in_sfas_date' => 'nullable|date_format:Y-m-d',
            'reconciled_with_payment_report_date' => 'nullable|date_format:Y-m-d',
            'reconciled_with_galaxy_date' => 'nullable|date_format:Y-m-d',
            'comment' => 'nullable|string',
        ];
    }
//
//    /**
//     * Prepare the data for validation.
//     *
//     * @return void
//     */
//    protected function prepareForValidation()
//    {
//        $this->merge([
//            'created_by' => Str::upper(Auth::user()->user_id),
//            'updated_by' => Str::upper(Auth::user()->user_id),
//        ]);
//    }
}

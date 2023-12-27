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
            'app_idx.*' => 'Missing connection to SFAS App.',
            'pay_idx.*' => 'Missing connection to SFAS Payment.',
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
            'app_idx' => 'required|numeric',
            'pay_idx' => 'required|numeric',
            'reconciled_with_payment_report_date' => 'nullable|date_format:Y-m-d',
            'reconciled_with_galaxy_date' => 'nullable|date_format:Y-m-d',
            'comment' => 'nullable|string',
            'profession' => 'nullable',
            'employer' => 'nullable',
            'employment_status' => 'nullable',
            'community' => 'nullable',
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

<?php

namespace Modules\Lfp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IntakeEditRequest extends FormRequest
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
            'sin' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'alias_name' => 'nullable',
            'profession' => 'nullable',
            'employer' => 'nullable',
            'community' => 'nullable',
            'employment_status' => 'nullable',
            'intake_status' => 'nullable',
            'in_good_standing' => 'nullable',
            'repayment_start_date' => 'nullable|date_format:Y-m-d',
            'receive_date' => 'nullable|date_format:Y-m-d',
            'proposed_registration_date' => 'nullable|date_format:Y-m-d',
            'denial_reason' => 'nullable',
            'amount_owing' => 'nullable|numeric',
            'comment' => 'nullable|string',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['sin' => str_replace(' ', '', ($this->sin))]);
    }
}

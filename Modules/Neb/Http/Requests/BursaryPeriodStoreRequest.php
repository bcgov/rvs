<?php

namespace Modules\Neb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BursaryPeriodStoreRequest extends FormRequest
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
            'bursary_period_start_date' => 'required|unique:'.env('DB_DATABASE_NEB').'.bursary_periods,bursary_period_start_date',
            'bursary_period_end_date' => 'required|unique:'.env('DB_DATABASE_NEB').'.bursary_periods,bursary_period_end_date',
            'default_award' => 'numeric|nullable',
            'period_budget' => 'numeric|nullable',
            'budget_allocation_type' => 'required|in:Sector,None,Nurse Type',
            'public_sector_budget' => 'numeric|min:0|max:100',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
    }
}

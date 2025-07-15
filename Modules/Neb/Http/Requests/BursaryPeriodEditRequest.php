<?php

namespace Modules\Neb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BursaryPeriodEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'bursary_period_start_date' => 'required|unique:'.env('DB_DATABASE_NEB').'.bursary_periods,bursary_period_start_date,'.$this->id,
            'bursary_period_end_date' => 'required|unique:'.env('DB_DATABASE_NEB').'.bursary_periods,bursary_period_end_date,'.$this->id,
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
    protected function prepareForValidation(): void {
        if (isset($this->application_status) && $this->application_status != 'DENIED') {
            $this->merge(['denial_reason' => null]);
        }
    }
}

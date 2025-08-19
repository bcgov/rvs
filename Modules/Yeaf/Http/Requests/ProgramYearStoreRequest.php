<?php

namespace Modules\Yeaf\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;

class ProgramYearStoreRequest extends FormRequest
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
    #[Override]
    public function messages(): array {
        return [
            'year_start.required' => 'Year Start field is required.',
            'year_start.unique' => 'The provided Year Start is already in-use.',
            'year_end.required' => 'Year End field is required.',
            'year_end.unique' => 'The provided Year End is already in-use.',
            'grant_amount.*' => 'Grant Amount field is required.',
            'max_years_allowed.*' => 'Active field is invalid.',
            'min_age.*' => 'Type field is required.',
            'max_age.*' => 'Description field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'year_start' => 'required|numeric|unique:'.env('DB_DATABASE_YEAF').'.program_years,year_start',
            'year_end' => 'required|numeric|unique:'.env('DB_DATABASE_YEAF').'.program_years,year_end',
            'grant_amount' => 'required|numeric',
            'max_years_allowed' => 'required|numeric',
            'min_age' => 'required|numeric',
            'max_age' => 'required|numeric',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        if (isset($this->year_start)) {
            $this->merge(['year_start' => preg_replace('/\D/', '', $this->year_start)]);
        }
        if (isset($this->year_end)) {
            $this->merge(['year_end' => preg_replace('/\D/', '', $this->year_end)]);
        }
        if (isset($this->max_years_allowed)) {
            $this->merge(['max_years_allowed' => preg_replace('/\D/', '', $this->max_years_allowed)]);
        }
        if (isset($this->min_age)) {
            $this->merge(['min_age' => preg_replace('/\D/', '', $this->min_age)]);
        }
        if (isset($this->max_age)) {
            $this->merge(['max_age' => preg_replace('/\D/', '', $this->max_age)]);
        }
    }
}

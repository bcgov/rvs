<?php

namespace Modules\Vss\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;

class InstitutionStoreRequest extends FormRequest
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
            'institution_code.required' => 'Institution Code field is required.',
            'institution_code.size' => 'Institution Code field must be of 4 characters.',
            'institution_code.unique' => 'Institution Code is already in use.',

            'institution_name.*' => 'Institution Name field is required.',

            'institution_location_code.required' => 'Institution Location Code field is required.',
            'institution_location_code.max' => 'Institution Location Code field must be maximum of 2 characters.',

            'institution_type_code.required' => 'Institution Type Code field is required.',
            'institution_type_code.max' => 'Institution Type Code field must be maximum of 2 characters.',

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        $school_code_rule = 'required|size:4|unique:'.env('DB_DATABASE_VSS').'.institutions,institution_code';
        if (isset($this->id)) {
            $school_code_rule = 'required|size:4|unique:'.env('DB_DATABASE_VSS').'.institutions,institution_code,'.$this->id.',id';
        }

        return [
            'institution_code' => $school_code_rule,
            'institution_name' => 'required',
            'institution_location_code' => 'required|max:2',
            'institution_type_code' => 'required|max:2',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        if (isset($this->institution_code)) {
            $this->merge(['institution_code' => mb_strtoupper($this->institution_code)]);
        }
        if (isset($this->institution_location_code)) {
            $this->merge(['institution_location_code' => mb_strtoupper($this->institution_location_code)]);
        }
        if (isset($this->institution_type_code)) {
            $this->merge(['institution_type_code' => mb_strtoupper($this->institution_type_code)]);
        }
    }
}

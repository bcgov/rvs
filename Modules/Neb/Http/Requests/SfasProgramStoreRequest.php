<?php

namespace Modules\Neb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SfasProgramStoreRequest extends FormRequest
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
            'sfas_program_code' => 'required|unique:'.env('DB_DATABASE_NEB').'.sfas_programs,sfas_program_code|max:4',
            'area_of_study' => 'required|min:3',
            'degree_level' => 'in:Certificate,Co-op Non-Degree,Co-op Undergraduate,Diploma,Doctorate,Masters,Non-Degree,Undergraduate',
            'nurse_type' => 'in:LPN,RN',
            'eligible' => 'boolean',
            'neb_program_code' => 'required|exists:'.env('DB_DATABASE_NEB').'.programs,program_code',
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

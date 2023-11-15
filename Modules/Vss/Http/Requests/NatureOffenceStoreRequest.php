<?php

namespace Modules\Vss\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NatureOffenceStoreRequest extends FormRequest
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
            'nature_code.required' => 'Nature Offence Code field is required.',
            'nature_code.unique' => 'Nature Offence Code is already in use.',

            'description.*' => 'Description field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nature_code_rule = 'required|unique:'.env('DB_DATABASE_VSS').'.nature_offences,nature_code';
        if (isset($this->id)) {
            $nature_code_rule = 'required|unique:'.env('DB_DATABASE_VSS').'.nature_offences,nature_code,'.$this->id.',id';
        }

        return [
            'nature_code' => $nature_code_rule,
            'description' => 'required',
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

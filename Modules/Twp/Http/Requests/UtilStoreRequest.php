<?php

namespace Modules\Twp\Http\Requests;

use Modules\Twp\Entities\Util;
use Illuminate\Foundation\Http\FormRequest;

class UtilStoreRequest extends FormRequest
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
            'field_name.*' => 'Title is not valid.',
            'field_type.*' => 'Type is not valid.',
            'active_flag.*' => 'Active is not valid.',
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
            'field_name' => 'required',
            'field_type' => 'required',
            'field_description' => 'nullable',
            'active_flag' => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['active_flag' => $this->toBoolean($this->active_flag)]);

        // Program Length Type values must be stored all in lower cases
        if ($this->field_type === 'Program Length Type') {
            $this->merge([
                'field_name' => strtolower($this->field_name),
            ]);
        }
        // Application Status values must be stored all in upper cases
        elseif ($this->field_type === 'Application Status') {
            $this->merge([
                'field_name' => strtoupper($this->field_name),
            ]);
        }
    }

    /**
     * Convert to boolean
     *
     * @return bool
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

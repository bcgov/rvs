<?php

namespace Modules\Twp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class InstitutionStoreRequest extends FormRequest
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
            'name.*' => 'Name field is not valid.',
            'active_flag.required' => 'Status field is required.',
            'active_flag.boolean' => 'Status field is invalid.',
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
            'active_flag' => 'required|boolean',
            'name' => 'string',
            'contact_name' => 'string|nullable',
            'contact_email' => 'string|email|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->contact_name)) {
            $this->merge(['contact_name' => Str::title($this->contact_name)]);
        }
        if (isset($this->contact_email)) {
            $this->merge(['contact_email' => Str::lower($this->contact_email)]);
        }

        $this->merge(['active_flag' => $this->toBoolean($this->active_flag)]);
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

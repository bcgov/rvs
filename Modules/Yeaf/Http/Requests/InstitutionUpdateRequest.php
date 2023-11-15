<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class InstitutionUpdateRequest extends FormRequest
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
            'name.*' => 'Institution Name field is not valid.',
            'city.*' => 'City field is not valid.',
            'address.*' => 'Address field is not valid.',
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
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',

            'country' => 'size:3|nullable|exists:'.env('DB_DATABASE_YEAF').'.countries,country_code',
            'province' => 'size:2|nullable|exists:'.env('DB_DATABASE_YEAF').'.provinces,province_code',

            'postal_code' => 'string|max:9|nullable',
            'tele' => 'max:15|string|nullable',
            'fax' => 'max:15|string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->postal_code)) {
            $this->merge(['postal_code' => Str::upper(preg_replace('/\s/', '', $this->postal_code))]);
        }

        if (isset($this->city)) {
            $this->merge(['city' => Str::title($this->city)]);
        }
        if (isset($this->name)) {
            $this->merge(['name' => Str::title($this->name)]);
        }

        if (isset($this->tele)) {
            $this->merge(['tele' => preg_replace('/\D/', '', $this->tele)]);
        }
        if (isset($this->fax)) {
            $this->merge(['fax' => preg_replace('/\D/', '', $this->fax)]);
        }
    }
}

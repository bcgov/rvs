<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Modules\Yeaf\Entities\Institution;

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
            'name.required' => 'Institution Name field is required.',
            'name.string' => 'Institution Name field is not valid.',
            'name.unique' => 'Institution with the same name already exists.',
            'city.*' => 'City field is not valid.',
            'address.*' => 'Address field is not valid.',
            'institution_id.*' => 'Institution ID is missing.',
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
            'institution_id' => 'required',
            'name' => 'required|string|unique:'.env('DB_DATABASE_YEAF').'.institutions,name',
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

        $last_institution = Institution::orderByDesc('institution_id')->first();
        $this->merge(['institution_id' => $last_institution->institution_id + 1]);
    }
}

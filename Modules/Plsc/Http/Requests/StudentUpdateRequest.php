<?php

namespace Modules\Plsc\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StudentUpdateRequest extends FormRequest
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
            'sin.required' => 'The SIN field is required.',
            'sin.digits' => 'The SIN length must be exactly 9 with no spaces.',
            'sin.unique' => 'The SIN provided is already in use.',
            'pen.*' => 'PEN field is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'sin' => 'required|digits:9|unique:'.env('DB_DATABASE_PLSC').'.students,sin,' . $this->id,

            'email' => 'email|nullable',
            'gender' => 'size:1|nullable',
            'pen' => 'max:9|nullable',
            'address1' => 'nullable',
            'address2' => 'nullable',
            'city' => 'nullable',
            'postal_code' => 'nullable',
            'province' => 'nullable',
            'country' => 'nullable',
            'phone_number' => 'nullable',
            'comment' => 'nullable',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        if (isset($this->birth_date)) {
            $this->merge(['birth_date' => date('Y-m-d', strtotime($this->birth_date))]);
        }

        if (isset($this->first_name)) {
            $this->merge(['first_name' => Str::title($this->first_name)]);
        }
        if (isset($this->last_name)) {
            $this->merge(['last_name' => Str::title($this->last_name)]);
        }
        if (isset($this->postal_code)) {
            $this->merge(['postal_code' => Str::upper($this->postal_code)]);
        }
        if (isset($this->city)) {
            $this->merge(['city' => Str::title($this->city)]);
        }

        if (isset($this->sin)) {
            //\D means "anything that isn't a digit":
            $this->merge(['sin' => preg_replace('/\D/', '', $this->sin)]);
        }
        if (isset($this->phone_number)) {
            $this->merge(['phone_number' => preg_replace('/\D/', '', $this->phone_number)]);
        }
    }
}

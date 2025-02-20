<?php

namespace Modules\Twp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
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
            'sin.required' => 'The SIN field is required.',
            'sin.digits' => 'The SIN length must be exactly 9 with no spaces.',
            'sin.unique' => 'The SIN provided is already in use.',
            'student_id.*' => 'Student ID is missing.',
            'tele.*' => 'Telephone number field is not valid.',
            'pen.*' => 'PEN field is not valid.',
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
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'alias_name' => 'nullable|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'email' => 'email|nullable',
            'gender' => 'size:1|nullable',
            'pen' => 'max:9|nullable',
            'sin' => 'max:9|nullable',
            'citizenship' => 'nullable',
            'bc_resident' => 'nullable',
            'indigeneity' => 'nullable',
            'address' => 'nullable',
            'comment' => 'nullable',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->birth_date)) {
            $this->merge(['birth_date' => date('Y-m-d', strtotime($this->birth_date))]);
        }
        if (is_null($this->bc_resident)) {
            $this->merge(['bc_resident' => false]);
        }
    }
}

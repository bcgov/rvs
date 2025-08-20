<?php

namespace Modules\Twp\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;

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
    #[Override]
    protected function prepareForValidation(): void {
        if (isset($this->birth_date)) {
            $this->merge(['birth_date' => date('Y-m-d', strtotime($this->birth_date))]);
        }
    }
}

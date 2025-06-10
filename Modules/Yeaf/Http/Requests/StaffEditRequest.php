<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffEditRequest extends FormRequest
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
    public function messages(): array {
        return [
            'tele.*' => 'Telephone number field is not valid.',
            'access_type.*' => 'Access Type field is not valid.',
            'disabled.required' => 'Status field is required.',
            'disabled.boolean' => 'Status field is invalid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'disabled' => 'required|boolean',
            'access_type' => 'required|in:A,U',
            'tele' => 'max:15|string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        if (isset($this->tele)) {
            $this->merge(['tele' => preg_replace('/\D/', '', $this->tele)]);
        }

        $this->merge(['disabled' => $this->toBoolean($this->disabled)]);
    }

    /**
     * Convert to boolean
     *
     * @return bool
     */
    private function toBoolean($booleable): bool {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

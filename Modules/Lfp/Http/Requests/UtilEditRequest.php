<?php

namespace Modules\Lfp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UtilEditRequest extends FormRequest
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
            'id.*' => 'Utility ID field is not valid.',
            'field_name.*' => 'Title is not valid.',
            'field_type.*' => 'Type is not valid.',
            'active_flag.*' => 'Active is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'id' => 'required',
            'field_name' => 'required',
            'field_type' => 'required',
            'active_flag' => 'required|boolean',

        ];
    }


    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        $this->merge(['active_flag' => $this->toBoolean($this->active_flag)]);
    }

    /**
     * Convert to boolean
     *
     * @param bool $booleable
     *
     * @return bool
     */
    private function toBoolean(bool $booleable): bool {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

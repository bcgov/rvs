<?php

namespace Modules\Yeaf\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;

class IneligibleEditRequest extends FormRequest
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
            'code_id.required' => 'Code ID field is required.',
            'code_id.unique' => 'The provided Code ID is already in-use.',
            'active_flag.required' => 'Active field is required.',
            'active_flag.boolean' => 'Active field is invalid.',
            'code_type.*' => 'Type field is required.',
            'description.*' => 'Description field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'code_id' => 'required|unique:'.env('DB_DATABASE_YEAF').'.ineligibles,code_id,'.$this->id,
            'active_flag' => 'required|boolean',
            'code_type' => 'required|in:D,P',
            'description' => 'required',
            'paragraph_text' => 'string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        $this->merge(['active_flag' => $this->toBoolean($this->active_flag)]);
    }

    /**
     * Convert to boolean
     *
     * @return bool
     */
    private function toBoolean(mixed $booleable): bool {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

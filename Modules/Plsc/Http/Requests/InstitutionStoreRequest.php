<?php

namespace Modules\Plsc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class InstitutionStoreRequest extends FormRequest
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
            'name.required' => 'Institution Name field is required.',
            'name.string' => 'Institution Name field is not valid.',
            'name.unique' => 'Institution Name with the same name already exists.',
            'active_flag.required' => 'Institution Status field is required.',
            'active_flag.boolean' => 'Institution Status field is invalid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'active_flag' => 'required|boolean',
            'name' => 'required|string|unique:'.env('DB_DATABASE_TWP').'.institutions,name',
            'contact_name' => 'string|nullable',
            'contact_email' => 'string|email|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
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
     * @param mixed $booleable
     *
     * @return bool
     */
    private function toBoolean(mixed $booleable): bool {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

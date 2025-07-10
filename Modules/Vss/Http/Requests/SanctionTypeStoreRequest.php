<?php

namespace Modules\Vss\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Modules\Vss\Entities\SanctionType;

class SanctionTypeStoreRequest extends FormRequest
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
            'sanction_code.required' => 'Sanction Code field is required.',
            'sanction_code.unique' => 'Sanction Code is already in use.',

            'short_description.*' => 'Short Description field is required.',
            'description.*' => 'Description field is required.',
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
        $sanction_code_rule = 'required|unique:'.env('DB_DATABASE_VSS').'.sanction_types,sanction_code';
        if (isset($this->id)) {
            $sanction_code_rule = 'required|unique:'.env('DB_DATABASE_VSS').'.sanction_types,sanction_code,'.$this->id.',id';
        }

        return [
            'sanction_code' => $sanction_code_rule,
            'description' => 'required',
            'short_description' => 'required',
            'disabled' => 'required|boolean',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        //if we are creating new record
        if (! isset($this->id)) {
            $last = SanctionType::select('sanction_code')->orderBy('sanction_code', 'desc')->first();
            $this->merge(['sanction_code' => $last->sanction_code + 1]);
        }

        $this->merge([
            'disabled' => $this->input('disabled') == 'true',
        ]);
    }
}

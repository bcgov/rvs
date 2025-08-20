<?php

namespace Modules\Lfp\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LfpEditRequest extends FormRequest
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
            'sin.*' => 'SIN field is not valid.',
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
            'sin' => 'required',
            'profession' => 'nullable', 'employer' => 'nullable', 'employment_status' => 'nullable', 'community' => 'nullable',
            'declined_removed_reason' => 'nullable', 'risk_sharing_guaranteed' => 'nullable', 'direct_lend' => 'nullable',
            'app_idx' => 'nullable', 'full_name_alias' => 'nullable', 'comment' => 'nullable'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        if (isset($this->sin)) {
            //\D means "anything that isn't a digit":
            $this->merge(['sin' => preg_replace('/\D/', '', $this->sin)]);
        }

        if (isset($this->full_name_alias)) {
            $this->merge(['full_name_alias' => Str::title($this->full_name_alias)]);
        }
    }
}

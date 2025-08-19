<?php

namespace Modules\Yeaf\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GrantEditRequest extends FormRequest
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
            'grant_id.*' => 'Grant ID number field is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'grant_id' => 'required',
            'officer_user_id' => 'required',
            'last_evaluation_date' => 'required',
            'age' => 'nullable|numeric',
            'status_code' => 'nullable|in:A,P,D',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        $form = json_decode($this->frm);
        foreach ($form as $item => $value) {
            if (! is_object($value)) {
                $this->merge([$item => $value]);
            } else {
                $this->merge([$item => json_encode($value)]);
            }
        }
        if (is_null($this->officer_user_id)) {
            $this->merge(['officer_user_id' => Auth::user()->user_id]);
        }
        if (isset($this->age)) {
            $this->merge(['age' => preg_replace('/\D/', '', $this->age)]);
        }

        $this->merge(['frm' => null]);
        $this->merge(['last_evaluation_date' => date('Y-m-d', strtotime('now'))]);
    }
}

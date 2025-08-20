<?php

namespace Modules\Twp\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentStoreRequest extends FormRequest
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
            'student_id.*' => 'Student ID field is not valid.',
            'application_id.*' => 'Application ID field is not valid.',
            'program_id.*' => 'Program ID field is not valid.',
            'payment_date.*' => 'Payment Date field is not valid.',
            'payment_amount.*' => 'Payment Amount field is not valid.',
            'payment_type_id.*' => 'Payment Type field is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'student_id' => 'required',
            'application_id' => 'required|numeric',
            'payment_type_id' => 'required|numeric',
            'program_id' => 'nullable',
            'payment_date' => 'required|date_format:Y-m-d',
            'payment_amount' => 'required|numeric',
            'created_by' => 'required',
            'updated_by' => 'required',
            'comment' => 'nullable|string|max:255',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation(): void {
        $this->merge([
            'created_by' => Str::upper(Auth::user()->user_id),
            'updated_by' => Str::upper(Auth::user()->user_id),
        ]);
    }
}

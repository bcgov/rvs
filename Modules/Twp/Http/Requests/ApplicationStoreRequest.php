<?php

namespace Modules\Twp\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Twp\Entities\Util;

class ApplicationStoreRequest extends FormRequest
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
            'received_date.*' => 'Application Received Date field is not valid.',
            'application_status.*' => 'Application Status field is not valid.',
            'twp_status.*' => 'TWP Status field is not valid.',
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
            'received_date' => 'date_format:Y-m-d|nullable',
            'application_status' => 'nullable|exists:'.env('DB_DATABASE_TWP').'.utils,field_name,field_type,Application Status',
            'denial_reason' => 'nullable',
            'exception_comments' => 'nullable',
            'institution_student_number' => 'nullable', 'apply_twp' => 'nullable', 'apply_lfg' => 'nullable',
            'confirmation_enrolment' => 'nullable', 'sabc_app_number' => 'nullable',
            'fao_name' => 'nullable', 'fao_email' => 'nullable|email', 'fao_phone' => 'nullable',
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
    }
}

<?php

namespace Modules\Twp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'received_date.*' => 'Application Received Date field is not valid.',
            'application_status.*' => 'Application Status field is not valid.',
            'twp_status.*' => 'TWP Status field is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'student_id' => 'required',
            'received_date' => 'date_format:Y-m-d|nullable',
            'application_status' => 'in:APPROVED,DENIED,IN PROGRESS,APPROVED ON APPEAL,APPROVED ON EXCEPTION,WITHDRAWN|nullable',
            'denial_reason' => 'nullable',
            'exception_comments' => 'nullable',

            'institution_student_number' => 'nullable', 'apply_twp' => 'nullable', 'apply_lfg' => 'nullable',
            'confirmation_enrolment' => 'nullable', 'sabc_app_number' => 'nullable',
            'fao_name' => 'nullable', 'fao_email' => 'nullable|email', 'fao_phone' => 'nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
    }
}

<?php

namespace Modules\Plsc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationEditRequest extends FormRequest
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
            'id.*' => 'Application is missing fields.',
            'student_id.*' => 'Student field is not valid.',
            'institution_id.*' => 'Institution field is not valid.',
            'receive_date.*' => 'Application Receive Date field is not valid.',
            'ssd.*' => 'Study Start Date field is not valid.',
            'sed.*' => 'Study End Date field is not valid.',
            'application_status.*' => 'Application Status field is not valid.',
            'status_code.*' => 'Status code field is not valid.',
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
            'student_id' => 'required',
            'institution_id' => 'required',
            'status_code' => 'in:DCLN,INTF,DONE|nullable',

            'receive_date' => 'date_format:Y-m-d|nullable',
            'ssd' => 'date_format:Y-m-d|nullable',
            'sed' => 'date_format:Y-m-d|nullable',

            'application_year' => 'digits:4|nullable',

            'program_of_study' => 'nullable', 'credential' => 'nullable', 'parent_last_name' => 'nullable',
            'parent_first_name' => 'nullable', 'parent_employee_id' => 'nullable', 'parent_department_id' => 'nullable',
            'parent_address' => 'nullable', 'parent_city' => 'nullable', 'parent_province' => 'nullable',
            'parent_postal_code' => 'nullable', 'parent_phone_number' => 'nullable', 'parent_email' => 'nullable',
            'parent_ministry' => 'nullable', 'parent_branch' => 'nullable', 'parent_job_title' => 'nullable',
            'parent_three_years_in_gov' => 'nullable', 'high_school_average' => 'nullable', 'post_secondary_average' => 'nullable',
            'seven_fifty_word_essay' => 'nullable', 'high_school_transcript' => 'nullable', 'post_secondary_transcript' => 'nullable',
            'student_reference_letter' => 'nullable', 'communication_skills' => 'nullable', 'enrollment_confirmed' => 'nullable',
            'forward_to_committee' => 'nullable', 'comment' => 'nullable', 'other_org' => 'nullable',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
    }
}

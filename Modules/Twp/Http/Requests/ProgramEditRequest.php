<?php

namespace Modules\Twp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramEditRequest extends FormRequest
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
            'id.*' => 'Program ID field is not valid.',
            'study_period_start_date.*' => 'Study Period Start Date field is not valid.',
            'student_status.*' => 'Student Status field is not valid.',
            'institution_twp_id.*' => 'The Institution Name and its ID are not valid.',
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
            'id' => 'required',
            'student_id' => 'required',
            'application_id' => 'required',
            'institution_name' => 'required',
            'institution_twp_id' => 'required',
            'credential' => 'nullable',
            'credential_type' => 'nullable',
            'study_field' => 'nullable',
            'study_period_start_date' => 'present|date_format:Y-m-d|nullable',
            'program_length' => 'present|numeric|nullable',
            'program_length_type' => 'present|in:day,week,month,year|nullable',
            'total_estimated_cost' => 'present|numeric|nullable',
            'student_status' => 'present|in:Attending,Completed,Hiatus,Never Attended,No Longer Attending|nullable',
            'comments' => 'nullable',
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

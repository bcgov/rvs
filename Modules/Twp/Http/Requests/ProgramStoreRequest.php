<?php

namespace Modules\Twp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Twp\Entities\Util;
use Illuminate\Support\Str;

class ProgramStoreRequest extends FormRequest
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
            'student_id' => 'required',
            'application_id' => 'required',
            'institution_name' => 'required',
            'institution_twp_id' => 'required',
            'credential' => 'nullable',
            'credential_type' => 'nullable',
            'study_field' => 'required',
            'study_period_start_date' => 'present|date_format:Y-m-d|nullable',
            'program_length' => 'present|numeric|nullable',
            'program_length_type' => 'nullable|exists:'.env('DB_DATABASE_TWP').'.utils,field_name,field_type,Program Length Type',
            'total_estimated_cost' => 'present|numeric|nullable',
            'student_status' => 'present|in:Attending,Completed,Hiatus,Never Attended,No Longer Attending|nullable',
            'comments' => 'nullable',
        ];
    }
}

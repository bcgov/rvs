<?php

namespace Modules\Yeaf\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Modules\Yeaf\Entities\Grant;

class GrantStoreRequest extends FormRequest
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
            'student_id.*' => 'Some fields are missing!',
            'institution_id.*' => 'Institution field is required.',
            'application_receive_date.*' => 'Date Received field is required.',
            'program_code.*' => 'Program Type field is required.',
            'program_year_id.*' => 'Program Year field is required.',
            'study_start_date.*' => 'Study Start Date field is required.',
            'study_end_date.*' => 'Study End Date field is required.',
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
            'institution_id' => 'required',
            'application_receive_date' => 'required',
            'program_code' => 'required',
            'program_year_id' => 'required',
            'study_start_date' => 'required',
            'study_end_date' => 'required',

            'grant_id' => 'required',
            'creator_user_id' => 'required',
            'update_user_id' => 'required',

            'officer_user_id' => 'required',
            'last_evaluation_date' => 'required',
            'age' => 'nullable|numeric',
            'status_code' => 'nullable|in:A,P,D',

            'application_type' => 'nullable|string|in:SFAS Extract,Paper Application',
            'program_name' => 'nullable|string',
            'application_number' => 'nullable|string',
            'program_other_description' => 'nullable',

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        if (isset($this->application_receive_date)) {
            $this->merge(['application_receive_date' => date('Y-m-d', strtotime($this->application_receive_date))]);
        }
        if (isset($this->study_start_date)) {
            $this->merge(['study_start_date' => date('Y-m-d', strtotime($this->study_start_date))]);
        }
        if (isset($this->study_end_date)) {
            $this->merge(['study_end_date' => date('Y-m-d', strtotime($this->study_end_date))]);
        }
        if (isset($this->age)) {
            $this->merge(['age' => preg_replace('/\D/', '', $this->age)]);
        }

        if (! isset($this->officer_user_id) || is_null($this->officer_user_id)) {
            $this->merge(['officer_user_id' => Auth::user()->user_id]);
        }
        $this->merge(['last_evaluation_date' => date('Y-m-d', strtotime('now'))]);

        $last_grant = Grant::select('grant_id')->orderBy('grant_id', 'desc')->first();
        $this->merge([
            'grant_id' => intval($last_grant->grant_id) + 1,
            'creator_user_id' => Str::upper(Auth::user()->user_id),
            'update_user_id' => Str::upper(Auth::user()->user_id),
        ]);

    }
}

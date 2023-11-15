<?php

namespace Modules\Vss\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Vss\Entities\Incident;

class CaseStoreRequest extends FormRequest
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
            'institution_code.required' => 'School field is required.',
            'institution_code.size' => 'School field is invalid',

            'incident_status.required' => 'Status Code field is required.',
            'incident_status.in' => 'Status Code field selected is invalid.',

            'referral_source_id.required' => 'Referral field is required.',

            'open_date.required' => 'Date Opened field is required.',
            'open_date.date_format' => 'Date Opened field is invalid. ',

            'year_of_audit.*' => 'Year of Audit is required in the format 20/21.',

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
            'sin' => 'required|digits:9',
            'incident_id' => 'required',

            'institution_code' => 'required|size:4',
            'open_date' => 'required|date_format:Y-m-d',
            'incident_status' => 'required|in:Active,Inactive,Re-activated',
            'severity' => 'required',
            'year_of_audit' => 'required|size:5',
            'audit_type' => 'required',
            'area_of_audit_code' => 'required',
            'referral_source_id' => 'required',

            'auditor_user_id' => 'string|nullable',
            'investigator_user_id' => 'string|nullable',
            'last_name' => 'string|nullable',
            'first_name' => 'string|nullable',
            'appeal_outcome' => 'string|nullable',
            'reason_for_closing' => 'string|nullable',
            'case_outcome' => 'string|nullable',
            'sentence_comment' => 'string|nullable',

            'reactivate_date' => 'date_format:Y-m-d|nullable',
            'bring_forward_date' => 'date_format:Y-m-d|nullable',
            'audit_date' => 'date_format:Y-m-d|nullable',
            'investigation_date' => 'date_format:Y-m-d|nullable',
            'rcmp_referral_date' => 'date_format:Y-m-d|nullable',
            'rcmp_closure_date' => 'date_format:Y-m-d|nullable',
            'close_date' => 'date_format:Y-m-d|nullable',

            'application_number' => 'digits:10|nullable',

            'bring_forward' => 'boolean',
            'appeal_flag' => 'boolean',
            'case_close' => 'boolean',
            'rcmp_referral_flag' => 'boolean',
            'charges_laid_flag' => 'boolean',
            'convicted_flag' => 'boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //Store won't have it, only Update
        if (! isset($this->incident_id)) {
            $last_incident = Incident::select('incident_id')->orderBy('incident_id', 'desc')->withTrashed()->first();
            $this->merge(['incident_id' => intval($last_incident->incident_id) + 1]);
        }

        if (isset($this->open_date)) {
            $this->merge(['open_date' => date('Y-m-d', strtotime($this->open_date))]);
        }

        if (isset($this->first_name)) {
            $this->merge(['first_name' => mb_strtoupper($this->first_name)]);
        }
        if (isset($this->last_name)) {
            $this->merge(['last_name' => mb_strtoupper($this->last_name)]);
        }

        if (isset($this->year_of_audit)) {
            if (strpos($this->year_of_audit, '/') == 2) {
                $this->merge([
                    'year_of_audit' => $this->year_of_audit,
                ]);
            } else {
                $this->merge([
                    'year_of_audit' => '',
                ]);
            }
        }
    }
}

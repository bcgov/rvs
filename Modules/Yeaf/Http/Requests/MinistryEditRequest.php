<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinistryEditRequest extends FormRequest
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
            'contact_fname' => 'required',
            'contact_lname' => 'required',
            'ministry' => 'required',
            'org' => 'required',


            'contact_tele' => 'nullable'
            ,'contact_title' => 'nullable'
            ,'contact_dept' => 'nullable'
            ,'contact_branch' => 'nullable'
            ,'ministry_branch' => 'nullable'
            ,'ministry_address' => 'nullable'
            ,'ministry_city' => 'nullable'
            ,'ministry_prov' => 'nullable'
            ,'ministry_postal' => 'nullable'
            ,'ministry_tele_victoria' => 'nullable'
            ,'ministry_tele_lower' => 'nullable'
            ,'ministry_tele_toll_free' => 'nullable'
            ,'ministry_TTY_line' => 'nullable'
            ,'ministry_location' => 'nullable'
            ,'ministry_location_city' => 'nullable'
            ,'ministry_location_prov' => 'nullable'
            ,'ministry_location_postal' => 'nullable'
            ,'ministry_location_tele_toll_free' => 'nullable'
            ,'ministry_fax' => 'nullable'
            ,'org_fname' => 'nullable'
            ,'org_lname' => 'nullable'
            ,'org_fax' => 'nullable'
            ,'user_fname' => 'nullable'
            ,'user_lname' => 'nullable'
            ,'user_branch' => 'nullable'
            ,'user_tele' => 'nullable'
            ,'user_fax' => 'nullable'
            ,'start_month' => 'nullable'
            ,'start_day' => 'nullable'
            ,'end_month' => 'nullable'
            ,'end_day' => 'nullable'
            ,'temp' => 'nullable'
            ,'application_name' => 'nullable'
            ,'application_abbreviation' => 'nullable'
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

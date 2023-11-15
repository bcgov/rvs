<?php

namespace Modules\Vss\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StaffEditRequest extends FormRequest
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
            'disabled.boolean' => 'Status field is invalid',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = User::find($this->id);

        return [
            'start_date' => 'required|string|max:255',
            'access_type' => 'required|string|max:1|in:A,U,S',
            'disabled' => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->disabled)) {
            $this->merge(['disabled' => filter_var($this->disabled, FILTER_VALIDATE_BOOLEAN)]);

            //remove end date if user is activated
            if ($this->disabled == false) {
                $this->merge(['end_date' => null]);
            } else {
                //add end date if the user is being de-activated and no end-date provided
                if (! isset($this->end_date)) {
                    $this->merge(['end_date' => date('Y-m-d', strtotime('now'))]);
                }
            }
        }

        if (isset($this->access_type)) {
            $this->merge(['access_type' => Str::upper($this->access_type)]);
        }

        if (isset($this->start_date)) {
            $this->merge(['start_date' => date('Y-m-d', strtotime($this->start_date))]);
        }
        if (isset($this->end_date)) {
            $this->merge(['end_date' => date('Y-m-d', strtotime($this->end_date))]);
        }
    }
}

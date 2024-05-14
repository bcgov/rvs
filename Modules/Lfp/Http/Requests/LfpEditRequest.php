<?php

namespace Modules\Lfp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LfpEditRequest extends FormRequest
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
            'sin.*' => 'SIN field is not valid.',
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
            'sin' => 'required',
            'profession' => 'nullable', 'employer' => 'nullable', 'employment_status' => 'nullable', 'community' => 'nullable',
            'declined_removed_reason' => 'nullable', 'risk_sharing_guaranteed' => 'nullable', 'direct_lend' => 'nullable',
            'app_idx' => 'nullable', 'full_name_alias' => 'nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->full_name_alias)) {
            $this->merge(['full_name_alias' => Str::title($this->full_name_alias)]);
        }
    }
}

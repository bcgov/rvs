<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IneligibleStoreRequest extends FormRequest
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
            'code_id.required' => 'Code ID field is required.',
            'code_id.unique' => 'The provided Code ID is already in-use.',
            'active_flag.required' => 'Active field is required.',
            'active_flag.boolean' => 'Active field is invalid.',
            'code_type.*' => 'Type field is required.',
            'description.*' => 'Description field is required.',
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
            'code_id' => 'required|unique:'.env('DB_DATABASE_YEAF').'.ineligibles,code_id',
            'active_flag' => 'required|boolean',
            'code_type' => 'required|in:D,P',
            'description' => 'required',
            'paragraph_text' => 'string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['active_flag' => $this->toBoolean($this->active_flag)]);
    }

    /**
     * Convert to boolean
     *
     * @return bool
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

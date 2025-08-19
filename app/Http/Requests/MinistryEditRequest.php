<?php

namespace App\Http\Requests;

use Override;
use Illuminate\Foundation\Http\FormRequest;

class MinistryEditRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [

            'name' => 'required'
            ,'branch' => 'nullable'
            ,'address' => 'nullable'
            ,'city' => 'nullable'
            ,'prov' => 'nullable'
            ,'postal' => 'nullable'
            ,'tele_victoria' => 'nullable'
            ,'tele_lower' => 'nullable'
            ,'tele_toll_free' => 'nullable'
            ,'TTY_line' => 'nullable'
            ,'location' => 'nullable'
            ,'location_city' => 'nullable'
            ,'location_prov' => 'nullable'
            ,'location_postal' => 'nullable'
            ,'location_tele_toll_free' => 'nullable'
            ,'fax' => 'nullable'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[Override]
    protected function prepareForValidation()
    {
    }
}

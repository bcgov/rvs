<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchEditRequest extends FormRequest
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
            'batch_number.required' => 'Batch Number field is required.',
            'batch_number.unique' => 'The provided Batch Number is already in-use.',
            'batch_date.required' => 'Batch Date field is required.',
            'batch_date.unique' => 'The provided Batch Date is already in-use.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        return [
            'batch_number' => 'required|between:2002080,2052080|numeric|unique:'.env('DB_DATABASE_YEAF').'.batches,batch_number,'.$this->id,
            'batch_date' => 'required|date|date_format:Y-m-d|unique:'.env('DB_DATABASE_YEAF').'.batches,batch_date,'.$this->id,
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        if (isset($this->batch_number)) {
            $this->merge(['batch_number' => preg_replace('/\D/', '', $this->batch_number)]);
        }
    }
}

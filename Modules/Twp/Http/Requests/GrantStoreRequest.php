<?php

namespace Modules\Twp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Twp\Entities\Util;

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
            'student_id.*' => 'Student ID field is not valid.',
            'received_date.*' => 'Grant Date field is not valid.',
            'grant_amount.*' => 'Grant Amount field is not valid.',
            'grant_comments.*' => 'Grant Comment field is not valid.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array {
        // Get the list of Application Status stored in the DB
        $applicationStatuses = Util::where('field_type', 'Application Status')
            ->pluck('field_name')
            ->toArray();

        $applicationStatusesString = implode(',', $applicationStatuses);

        return [
            'student_id' => 'required',
            'application_id' => 'required',
            'received_date' => 'present|date_format:Y-m-d|nullable',
            'grant_status' => 'in:' . $applicationStatusesString . '|nullable',
            'grant_amount' => 'present|numeric|nullable',
            'grant_comments' => 'nullable',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        $this->merge([
            'created_by' => Str::upper(Auth::user()->user_id),
            'updated_by' => Str::upper(Auth::user()->user_id),
        ]);
    }
}

<?php

namespace Modules\Yeaf\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CommentStoreRequest extends FormRequest
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
            'user_id.*' => 'Required field is missing.',
            'comment_text.*' => 'Comment field is required.',
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
            'user_id' => 'required',
            'comment_text' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        $user = Auth::user();

        if ($user) {
            $this->merge([
                'user_id' => Str::upper($user->user_id),
            ]);
        }
    }
}

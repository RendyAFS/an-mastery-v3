<?php

namespace App\Http\Requests\MyProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateMyPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password'  => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required'  => __('my-profile.validation.password.required'),
            'password.string'    => __('my-profile.validation.password.string'),
            'password.confirmed' => __('my-profile.validation.password.confirmed'),
            'password.min'       => __('my-profile.validation.password.min'),
            'password.letters'   => __('my-profile.validation.password.letters'),
            'password.numbers'   => __('my-profile.validation.password.numbers'),
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => __('my-profile.form.password'),
        ];
    }
}

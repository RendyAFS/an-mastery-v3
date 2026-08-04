<?php

namespace App\Http\Requests\MyProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMyProfileRequest extends FormRequest
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
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => __('my-profile.validation.name.required'),
            'name.string'    => __('my-profile.validation.name.string'),
            'name.max'       => __('my-profile.validation.name.max'),
            'email.required' => __('my-profile.validation.email.required'),
            'email.email'    => __('my-profile.validation.email.email'),
            'email.unique'   => __('my-profile.validation.email.unique'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => __('my-profile.form.name'),
            'email' => __('my-profile.form.email'),
        ];
    }
}

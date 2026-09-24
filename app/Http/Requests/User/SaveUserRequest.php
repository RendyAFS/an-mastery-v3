<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'      => ['required', 'string', 'unique:users,name,' . $userId . ',id,deleted_at,NULL'],
            'email'     => ['required', 'email', 'unique:users,email,' . $userId . ',id,deleted_at,NULL'],
            'password'  => [$userId ? 'sometimes' : 'required', 'nullable', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'roles'     => ['nullable', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => __('user.validation.name.required'),
            'name.string'        => __('user.validation.name.string'),
            'name.unique'        => __('user.validation.name.unique'),
            'email.required'     => __('user.validation.email.required'),
            'email.email'        => __('user.validation.email.email'),
            'email.unique'       => __('user.validation.email.unique'),
            'password.required'  => __('user.validation.password.required'),
            'password.confirmed' => __('user.validation.password.confirmed'),
            'is_active.boolean'  => __('user.validation.is_active.boolean'),
            'roles.exists'       => __('user.validation.roles.exists'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'      => __('user.form.name'),
            'email'     => __('user.form.email'),
            'password'  => __('user.form.password'),
            'is_active' => __('user.form.is_active'),
            'roles'     => __('user.form.roles'),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SaveUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $userId,
            'password'  => [$userId ? 'nullable' : 'required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'is_active' => 'nullable|boolean',
            'roles'     => 'nullable|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Name is required.',
            'email.required'     => 'Email is required.',
            'email.email'        => 'Email is invalid.',
            'email.unique'       => 'Email already exists.',
            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 8 characters and has numeric.',
            'is_active.boolean'  => 'Status is required.',
            'roles.array'        => 'Roles must be an array.',
            'roles.*.exists'     => 'Role is invalid.',
        ];
    }
}

<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['nullable', 'string'],
            'contact'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'notes'     => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => __('employee.validation.name.required'),
            'address.required' => __('employee.validation.address.required'),
            'contact.string'   => __('employee.validation.contact.string'),
            'notes.string'     => __('employee.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'      => __('employee.form.name'),
            'address'   => __('employee.form.address'),
            'contact'   => __('employee.form.contact'),
            'is_active' => __('employee.form.is_active'),
            'notes'     => __('employee.form.notes'),
        ];
    }
}

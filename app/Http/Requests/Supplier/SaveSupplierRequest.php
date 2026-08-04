<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class SaveSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['required', 'string'],
            'contact'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'notes'     => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => __('supplier.validation.name.required'),
            'address.required' => __('supplier.validation.address.required'),
            'contact.string'   => __('supplier.validation.contact.string'),
            'notes.string'     => __('supplier.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'      => __('supplier.form.name'),
            'address'   => __('supplier.form.address'),
            'contact'   => __('supplier.form.contact'),
            'is_active' => __('supplier.form.is_active'),
            'notes'     => __('supplier.form.notes'),
        ];
    }
}

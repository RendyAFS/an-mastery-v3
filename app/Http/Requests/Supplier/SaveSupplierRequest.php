<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class SaveSupplierRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'contact'   => 'nullable|string',
            'notes'     => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Name is required.',
            'address.required'   => 'Address is required.',
            'contact.string'     => 'Contact must be a string.',
            'notes.string'       => 'Notes must be a string.',
        ];
    }
}

<?php

namespace App\Http\Requests\ColorFabric;

use Illuminate\Foundation\Http\FormRequest;

class SaveColorFabricRequest extends FormRequest
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
            'name'       => 'required|string|max:255',
            'code_color' => 'nullable|string|max:255',
            'notes'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Name is required.',
            'code_color.required' => 'Code Color is required.',
            'notes.string'        => 'Notes must be a string.',
        ];
    }
}

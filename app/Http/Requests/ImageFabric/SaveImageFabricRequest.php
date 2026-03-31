<?php

namespace App\Http\Requests\ImageFabric;

use Illuminate\Foundation\Http\FormRequest;

class SaveImageFabricRequest extends FormRequest
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
            'name'         => 'required|string|max:255',
            'notes'        => 'nullable|string',
            'image_tmp'    => ['nullable', 'string'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Name is required.',
            'name.string'    => 'Name must be a string.',
            'name.max'       => 'Name must be less than 255 characters.',
            'notes.string'   => 'Notes must be a string.',
        ];
    }
}

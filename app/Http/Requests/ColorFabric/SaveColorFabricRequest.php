<?php

namespace App\Http\Requests\ColorFabric;

use Illuminate\Foundation\Http\FormRequest;

class SaveColorFabricRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'code_color' => ['nullable', 'string', 'max:255'],
            'notes'      => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => __('color-fabric.validation.name.required'),
            'code_color.required' => __('color-fabric.validation.code_color.required'),
            'notes.string'        => __('color-fabric.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => __('color-fabric.form.name'),
            'code_color' => __('color-fabric.form.code_color'),
            'notes'      => __('color-fabric.form.notes'),
        ];
    }
}

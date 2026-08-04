<?php

namespace App\Http\Requests\TypeColor;

use Illuminate\Foundation\Http\FormRequest;

class SaveTypeColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('type-color.validation.name.required'),
            'name.integer'  => __('type-color.validation.name.integer'),
            'name.min'      => __('type-color.validation.name.min'),
            'notes.string'  => __('type-color.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => __('type-color.form.name'),
            'notes' => __('type-color.form.notes'),
        ];
    }
}

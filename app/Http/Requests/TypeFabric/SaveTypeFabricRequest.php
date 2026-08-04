<?php

namespace App\Http\Requests\TypeFabric;

use Illuminate\Foundation\Http\FormRequest;

class SaveTypeFabricRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('type-fabric.validation.name.required'),
            'name.string'   => __('type-fabric.validation.name.string'),
            'name.max'      => __('type-fabric.validation.name.max'),

            'notes.string'  => __('type-fabric.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => __('type-fabric.form.name'),
            'notes' => __('type-fabric.form.notes'),
        ];
    }
}

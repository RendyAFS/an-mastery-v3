<?php

namespace App\Http\Requests\ImageFabric;

use Illuminate\Foundation\Http\FormRequest;

class SaveImageFabricRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'notes'        => ['nullable', 'string'],
            'image_tmp'    => ['nullable', 'string'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('image-fabric.validation.name.required'),
            'name.string'   => __('image-fabric.validation.name.string'),
            'name.max'      => __('image-fabric.validation.name.max'),
            'notes.string'  => __('image-fabric.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'         => __('image-fabric.form.name'),
            'notes'        => __('image-fabric.form.notes'),
            'image_tmp'    => __('image-fabric.form.image'),
            'remove_image' => __('image-fabric.form.remove_image'),
        ];
    }
}

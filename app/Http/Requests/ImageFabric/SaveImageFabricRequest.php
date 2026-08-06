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
            'name'             => ['required', 'string', 'max:255'],
            'notes'            => ['nullable', 'string'],
            'images_tmp'       => ['nullable', 'array'],
            'images_tmp.*'     => ['string'],
            'removed_images'   => ['nullable', 'array'],
            'removed_images.*' => ['integer'],
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
            'name'           => __('image-fabric.form.name'),
            'notes'          => __('image-fabric.form.notes'),
            'images_tmp'     => __('image-fabric.form.image'),
            'removed_images' => __('image-fabric.form.remove_image'),
        ];
    }
}

<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class SaveGalleryRequest extends FormRequest
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
            'name.required' => __('gallery.validation.name.required'),
            'name.string'   => __('gallery.validation.name.string'),
            'name.max'      => __('gallery.validation.name.max'),
            'notes.string'  => __('gallery.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'           => __('gallery.form.name'),
            'notes'          => __('gallery.form.notes'),
            'images_tmp'     => __('gallery.form.image'),
            'removed_images' => __('gallery.form.remove_image'),
        ];
    }
}

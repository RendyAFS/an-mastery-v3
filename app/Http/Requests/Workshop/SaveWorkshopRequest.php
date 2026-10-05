<?php

namespace App\Http\Requests\Workshop;

use Illuminate\Foundation\Http\FormRequest;

class SaveWorkshopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'is_active'        => ['nullable', 'boolean'],
            'location'         => ['nullable', 'string'],
            'images_tmp'       => ['nullable', 'array'],
            'images_tmp.*'     => ['string'],
            'removed_images'   => ['nullable', 'array'],
            'removed_images.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('workshop.validation.name.required'),
            'name.string'   => __('workshop.validation.name.string'),
            'name.max'      => __('workshop.validation.name.max'),
            'location.string' => __('workshop.validation.location.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'           => __('workshop.form.name'),
            'is_active'      => __('workshop.form.is_active'),
            'location'       => __('workshop.form.location'),
            'images_tmp'     => __('workshop.form.image'),
            'removed_images' => __('workshop.form.remove_image'),
        ];
    }
}

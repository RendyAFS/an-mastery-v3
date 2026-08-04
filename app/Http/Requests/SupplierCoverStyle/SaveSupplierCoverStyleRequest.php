<?php

namespace App\Http\Requests\SupplierCoverStyle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveSupplierCoverStyleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'color_from' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_to'   => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'icon'       => ['required', Rule::in(config('cover-styles.icons'))],
            'pattern'    => ['required', Rule::in(array_keys(config('cover-styles.patterns')))],
        ];
    }

    public function messages(): array
    {
        return [
            'color_from.required' => __('supplier.validation.cover_style.color_from.required'),
            'color_from.regex'    => __('supplier.validation.cover_style.color_from.regex'),
            'color_to.required'   => __('supplier.validation.cover_style.color_to.required'),
            'color_to.regex'      => __('supplier.validation.cover_style.color_to.regex'),
            'icon.required'       => __('supplier.validation.cover_style.icon.required'),
            'icon.in'             => __('supplier.validation.cover_style.icon.in'),
            'pattern.required'    => __('supplier.validation.cover_style.pattern.required'),
            'pattern.in'          => __('supplier.validation.cover_style.pattern.in'),
        ];
    }

    public function attributes(): array
    {
        return [
            'color_from' => __('supplier.cover_style.color_from'),
            'color_to'   => __('supplier.cover_style.color_to'),
            'icon'       => __('supplier.cover_style.icon'),
            'pattern'    => __('supplier.cover_style.pattern'),
        ];
    }
}

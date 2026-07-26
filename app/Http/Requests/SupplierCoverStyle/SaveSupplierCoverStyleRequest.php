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
            'color_from.regex' => 'Format warna harus hex, contoh #6366f1.',
            'color_to.regex'   => 'Format warna harus hex, contoh #4338ca.',
            'icon.in'          => 'Icon tidak tersedia dalam daftar.',
            'pattern.in'       => 'Pattern tidak tersedia dalam daftar.',
        ];
    }
}

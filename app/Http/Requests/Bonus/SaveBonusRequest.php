<?php

namespace App\Http\Requests\Bonus;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveBonusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'min'   => ['required', 'numeric', 'min:0'],
            'bonus' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'min.required'   => __('bonus.validation.min.required'),
            'min.numeric'    => __('bonus.validation.min.numeric'),
            'min.min'        => __('bonus.validation.min.min'),
            'bonus.required' => __('bonus.validation.bonus.required'),
            'bonus.numeric'  => __('bonus.validation.bonus.numeric'),
            'bonus.min'      => __('bonus.validation.bonus.min'),
            'notes.string'   => __('bonus.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'min'   => __('bonus.fields.min'),
            'bonus' => __('bonus.fields.bonus'),
            'notes' => __('bonus.fields.notes'),
        ];
    }
}

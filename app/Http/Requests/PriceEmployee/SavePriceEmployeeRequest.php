<?php

namespace App\Http\Requests\PriceEmployee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SavePriceEmployeeRequest extends FormRequest
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
            'type_fabric_id' => ['required', 'exists:type_fabrics,id'],
            'type_color_id'  => ['required', 'exists:type_colors,id'],
            'price'          => ['required', 'numeric'],
            'notes'          => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'type_fabric_id.required' => __('price-employee.validation.type_fabric_id.required'),
            'type_fabric_id.exists'   => __('price-employee.validation.type_fabric_id.exists'),
            'type_color_id.required'  => __('price-employee.validation.type_color_id.required'),
            'type_color_id.exists'    => __('price-employee.validation.type_color_id.exists'),
            'price.required'          => __('price-employee.validation.price.required'),
            'price.numeric'           => __('price-employee.validation.price.numeric'),
            'notes.string'            => __('price-employee.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'type_fabric_id' => __('price-employee.form.type_fabric'),
            'type_color_id'  => __('price-employee.form.type_color'),
            'price'          => __('price-employee.form.price'),
            'notes'          => __('price-employee.form.notes'),
        ];
    }
}

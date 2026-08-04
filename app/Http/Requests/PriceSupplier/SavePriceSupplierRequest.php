<?php

namespace App\Http\Requests\PriceSupplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePriceSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $priceSupplier = $this->route('priceSupplier');

        return [
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
                Rule::unique('price_suppliers')
                    ->where(function ($query) {
                        return $query
                            ->where('type_fabric_id', $this->type_fabric_id)
                            ->where('type_color_id', $this->type_color_id);
                    })
                    ->ignore($priceSupplier?->id),
            ],

            'type_fabric_id' => 'required|exists:type_fabrics,id',
            'type_color_id'  => 'required|exists:type_colors,id',

            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'    => __('price-supplier.validation.supplier_required'),
            'supplier_id.exists'      => __('price-supplier.validation.supplier_exists'),
            'supplier_id.unique'      => __('price-supplier.validation.combination_unique'),

            'type_fabric_id.required' => __('price-supplier.validation.type_fabric_required'),
            'type_fabric_id.exists'   => __('price-supplier.validation.type_fabric_exists'),

            'type_color_id.required'  => __('price-supplier.validation.type_color_required'),
            'type_color_id.exists'    => __('price-supplier.validation.type_color_exists'),

            'price.required'          => __('price-supplier.validation.price_required'),
            'price.numeric'           => __('price-supplier.validation.price_numeric'),

            'notes.string'            => __('price-supplier.validation.notes_string'),
        ];
    }
}

<?php

namespace App\Http\Requests\PriceSupplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePriceSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $priceSupplier = $this->route('price_supplier');

        return [
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
                Rule::unique('price_suppliers')
                    ->where(fn($query) => $query
                        ->where('type_fabric_id', $this->type_fabric_id)
                        ->where('type_color_id', $this->type_color_id))
                    ->ignore($priceSupplier?->id),
            ],

            'type_fabric_id' => ['required', 'exists:type_fabrics,id'],
            'type_color_id'  => ['required', 'exists:type_colors,id'],
            'price'          => ['required', 'numeric'],
            'notes'          => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'    => __('price-supplier.validation.supplier_id.required'),
            'supplier_id.exists'      => __('price-supplier.validation.supplier_id.exists'),
            'supplier_id.unique'      => __('price-supplier.validation.supplier_id.unique'),
            'type_fabric_id.required' => __('price-supplier.validation.type_fabric_id.required'),
            'type_fabric_id.exists'   => __('price-supplier.validation.type_fabric_id.exists'),
            'type_color_id.required'  => __('price-supplier.validation.type_color_id.required'),
            'type_color_id.exists'    => __('price-supplier.validation.type_color_id.exists'),
            'price.required'          => __('price-supplier.validation.price.required'),
            'price.numeric'           => __('price-supplier.validation.price.numeric'),
            'notes.string'            => __('price-supplier.validation.notes.string'),
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id'     => __('price_supplier.form.supplier'),
            'type_fabric_id'  => __('price_supplier.form.type_fabric'),
            'type_color_id'   => __('price_supplier.form.type_color'),
            'price'           => __('price_supplier.form.price'),
            'notes'           => __('price_supplier.form.notes'),
        ];
    }
}

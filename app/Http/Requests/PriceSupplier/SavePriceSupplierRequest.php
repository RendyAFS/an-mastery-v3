<?php

namespace App\Http\Requests\PriceSupplier;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'supplier_id'    => 'required|exists:suppliers,id',
            'type_fabric_id' => 'required|exists:type_fabrics,id',
            'type_color_id'  => 'required|exists:type_colors,id',
            'price'          => 'required|numeric',
            'notes'          => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'    => 'Supplier is required.',
            'supplier_id.exists'      => 'Please choose valid Supplier.',
            'type_fabric_id.required' => 'Type Fabric is required.',
            'type_fabric_id.exists'   => 'Please choose valid Type Fabric.',
            'type_color_id.required'  => 'Type Color is required.',
            'type_color_id.exists'    => 'Please choose valid Type Color.',
            'price.required'          => 'Price is required.',
            'price.decimal'           => 'Price must be a decimal.',
            'notes.string'            => 'Notes must be a string.',
        ];
    }
}

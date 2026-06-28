<?php

namespace App\Http\Requests\Fabric;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveFabricRequest extends FormRequest
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
            'supplier_id'                      => 'required|exists:suppliers,id',
            'type_fabric_id'                   => 'required|exists:type_fabrics,id',
            'date_coming'                      => 'required|date',
            'seri'                             => 'required|integer|min:1',
            'notes'                            => 'nullable|string|max:255',
            // fabric_details
            'fabric_details'                   => 'required|array|min:1',
            'fabric_details.*.color_fabric_id' => 'required|exists:color_fabrics,id',
            'fabric_details.*.stock'           => 'required|numeric|min:0',
            'fabric_details.*.notes'           => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required'                      => 'Supplier is required',
            'supplier_id.exists'                        => 'Supplier does not exist',
            'type_fabric_id.required'                   => 'Type Fabric is required',
            'type_fabric_id.exists'                     => 'Type Fabric does not exist',
            'date_coming.required'                      => 'Date Comming is required',
            'date_coming.date'                          => 'Date Comming must be a date',
            'seri.required'                             => 'Seri is required',
            'seri.integer'                              => 'Seri must be a number',
            'seri.min'                                  => 'Seri must be at least 1',
            'notes.max'                                 => 'Notes must be at most 255 characters',
            // fabric_details
            'fabric_details.required'                   => 'At least one fabric detail is required',
            'fabric_details.*.color_fabric_id.required' => 'Color is required',
            'fabric_details.*.color_fabric_id.exists'   => 'Color does not exist',
            'fabric_details.*.stock.required'           => 'Stock is required',
            'fabric_details.*.stock.numeric'            => 'Stock must be a number',
            'fabric_details.*.stock.min'                => 'Stock must be at least 0',
        ];
    }
}

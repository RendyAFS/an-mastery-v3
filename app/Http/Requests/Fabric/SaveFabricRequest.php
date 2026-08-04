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
            'supplier_id'                      => ['required', 'exists:suppliers,id'],
            'type_fabric_id'                   => ['required', 'exists:type_fabrics,id'],
            'date_coming'                      => ['required', 'date'],
            'seri'                             => ['required', 'integer', 'min:1'],
            'notes'                            => ['nullable', 'string', 'max:255'],
            // Fabric Detail
            'fabric_details'                   => ['required', 'array', 'min:1'],
            'fabric_details.*.color_fabric_id' => ['required', 'exists:color_fabrics,id'],
            'fabric_details.*.stock'           => ['required', 'numeric', 'min:0'],
            'fabric_details.*.notes'           => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'                      => __('fabric.validation.supplier_id.required'),
            'supplier_id.exists'                        => __('fabric.validation.supplier_id.exists'),
            'type_fabric_id.required'                   => __('fabric.validation.type_fabric_id.required'),
            'type_fabric_id.exists'                     => __('fabric.validation.type_fabric_id.exists'),
            'date_coming.required'                      => __('fabric.validation.date_coming.required'),
            'date_coming.date'                          => __('fabric.validation.date_coming.date'),
            'seri.required'                             => __('fabric.validation.seri.required'),
            'seri.integer'                              => __('fabric.validation.seri.integer'),
            'seri.min'                                  => __('fabric.validation.seri.min'),
            'notes.max'                                 => __('fabric.validation.notes.max'),
            // Fabric Detail
            'fabric_details.required'                   => __('fabric.validation.fabric_details.required'),
            'fabric_details.*.color_fabric_id.required' => __('fabric.validation.fabric_details.color_fabric_id.required'),
            'fabric_details.*.color_fabric_id.exists'   => __('fabric.validation.fabric_details.color_fabric_id.exists'),
            'fabric_details.*.stock.required'           => __('fabric.validation.fabric_details.stock.required'),
            'fabric_details.*.stock.numeric'            => __('fabric.validation.fabric_details.stock.numeric'),
            'fabric_details.*.stock.min'                => __('fabric.validation.fabric_details.stock.min'),
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id'                      => __('fabric.form.supplier'),
            'type_fabric_id'                   => __('fabric.form.type_fabric'),
            'date_coming'                      => __('fabric.form.date_coming'),
            'seri'                             => __('fabric.form.seri'),
            'notes'                            => __('fabric.form.notes'),
            // Fabric Detail
            'fabric_details'                   => __('fabric.form.fabric_details'),
            'fabric_details.*.color_fabric_id' => __('fabric.form.color'),
            'fabric_details.*.stock'           => __('fabric.form.stock'),
            'fabric_details.*.notes'           => __('fabric.form.notes'),
        ];
    }
}

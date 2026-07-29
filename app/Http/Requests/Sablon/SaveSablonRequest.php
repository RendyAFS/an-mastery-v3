<?php

namespace App\Http\Requests\Sablon;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveSablonRequest extends FormRequest
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
            'supplier_id'                           => 'required|exists:suppliers,id',
            'fabric_id'                             => 'required|exists:fabrics,id',
            'image_fabric_id'                       => 'required|exists:image_fabrics,id',
            'type_color_id'                         => 'required|exists:type_colors,id',
            'type_fabric_id'                        => 'required|exists:type_fabrics,id',
            'price_employee_id'                     => 'required|exists:price_employees,id',
            'total_long_fabric'                     => 'nullable|numeric|min:0',
            'total_sablon'                          => 'nullable|numeric|min:0',
            'date_sablon'                           => 'required|date',
            'status'                                => 'required|in:ON_PROGRESS,DONE,DELIVERED,RETURNED',
            'notes'                                 => 'nullable|string|max:255',
            // Fabric details (warna & panjang kain yang dipakai)
            'fabric_details'                        => 'required|array|min:1',
            'fabric_details.*.fabric_detail_id'     => 'required|exists:fabric_details,id',
            'fabric_details.*.color_fabric_id'      => 'required|exists:color_fabrics,id',
            'fabric_details.*.long_fabric'          => 'nullable|numeric|min:0',
            // Employee details (siapa yang mengerjakan & fee-nya)
            'employee_details'                      => 'nullable|array',
            'employee_details.*.fabric_detail_id'   => 'nullable|exists:fabric_details,id',
            'employee_details.*.employee_id'        => 'required_with:employee_details|exists:employees,id',
            'employee_details.*.layers'             => 'nullable|integer|min:0',
            'employee_details.*.fee'                => 'nullable|numeric|min:0',
            'employee_details.*.additional_fee'     => 'nullable|array|min:0',
            'employee_details.*.total'              => 'nullable|numeric|min:0',
            'employee_details.*.is_change'          => 'nullable|boolean',
            'employee_details.*.employee_change_id' => 'nullable|exists:employees,id|different:employee_details.*.employee_id',
            'employee_details.*.is_bon'           => 'nullable|boolean',
            'employee_details.*.is_payed'           => 'nullable|boolean',
            'employee_details.*.notes'              => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'                           => 'Supplier must be selected.',
            'fabric_id.required'                             => 'Fabric type must be selected.',
            'image_fabric_id.required'                       => 'Fabric image must be selected.',
            'type_color_id.required'                         => 'Color type must be selected.',
            'type_fabric_id.required'                        => 'Fabric type must be selected.',
            'price_employee_id.required'                     => 'Employee price must be selected.',
            'total_long_fabric.required'                     => 'Total long fabric must be selected.',
            'total_sablon.required'                          => 'Total sablon must be selected.',
            'date_sablon.required'                           => 'Sablon date must be selected.',
            'status.required'                                => 'Status must be selected.',
            'status.in'                                      => 'Status is invalid.',
            'notes.max'                                      => 'Notes must be less than 255 characters.',
            // Fabric Details
            'fabric_details.required'                        => 'At least 1 fabric detail (color & length) must be filled.',
            'fabric_details.*.fabric_detail_id.required'     => 'Fabric detail must be selected.',
            'fabric_details.*.color_fabric_id.required'      => 'Fabric color must be selected.',
            'fabric_details.*.long_fabric.required'          => 'Fabric length must be selected.',
            // Employee Details
            'employee_details.required'                      => 'Employee detail must be filled.',
            'employee_details.*.fabric_detail_id.required'   => 'Fabric detail must be selected.',
            'employee_details.*.employee_id.required'        => 'Employee must be selected.',
            'employee_details.*.layers.required'             => 'Layer must be selected.',
            'employee_details.*.fee.required'                => 'Fee must be selected.',
            'employee_details.*.additional_fee.required'     => 'Additional fee must be selected.',
            'employee_details.*.total.required'              => 'Total must be selected.',
            'employee_details.*.is_change.required'          => 'Is change must be selected.',
            'employee_details.*.employee_change_id.required' => 'Changed employee must be selected.',
            'employee_details.*.is_payed.required'           => 'Is payed must be selected.',
            'employee_details.*.notes.max'                   => 'Notes must be less than 255 characters.',
        ];
    }
}

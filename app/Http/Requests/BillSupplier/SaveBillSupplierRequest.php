<?php

namespace App\Http\Requests\BillSupplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBillSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'date_bill'    => ['required', 'date'],
            'is_paid'      => ['nullable', 'boolean'],
            'is_delivered' => ['nullable', 'boolean'],
            'notes'        => ['nullable', 'string'],
            'sablon_ids'   => 'nullable|array',
            'sablon_ids.*' => 'exists:sablons,id',
        ];

        if ($this->isMethod('post')) {
            $rules['sablon_ids'] = ['required', 'array', 'min:1'];
            $rules['sablon_ids.*'] = [
                'integer',
                Rule::exists('sablons', 'id')->whereNull('deleted_at'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'sablon_ids.required' => __('bill_supplier.validation.sablon_ids.required'),
            'sablon_ids.array'    => __('bill_supplier.validation.sablon_ids.array'),
            'sablon_ids.min'      => __('bill_supplier.validation.sablon_ids.min'),
            'sablon_ids.*.exists' => __('bill_supplier.validation.sablon_ids.exists'),
            'date_bill.required'  => __('bill_supplier.validation.date_bill.required'),
            'date_bill.date'      => __('bill_supplier.validation.date_bill.date'),
        ];
    }

    public function attributes(): array
    {
        return [
            'date_bill'    => __('bill_supplier.form.date_bill'),
            'sablon_ids'   => __('bill_supplier.form.sablon_label_create'),
            'notes'        => __('bill_supplier.form.notes'),
            'is_paid'      => __('bill_supplier.form.is_paid'),
            'is_delivered' => __('bill_supplier.form.is_delivered'),
        ];
    }
}

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
            'date_bill' => ['required', 'date'],
            'is_paid'   => ['nullable', 'boolean'],
            'notes'     => ['nullable', 'string'],
        ];

        if ($this->isMethod('post')) {
            $rules['sablon_ids']   = ['required', 'array', 'min:1'];
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
            'sablon_ids.required' => 'Pilih minimal satu sablon.',
            'sablon_ids.array'    => 'Format sablon tidak valid.',
            'sablon_ids.min'      => 'Pilih minimal satu sablon.',
            'sablon_ids.*.exists' => 'Salah satu sablon tidak ditemukan.',
            'date_bill.required'  => 'Tanggal bill wajib diisi.',
            'date_bill.date'      => 'Format tanggal tidak valid.',
        ];
    }
}

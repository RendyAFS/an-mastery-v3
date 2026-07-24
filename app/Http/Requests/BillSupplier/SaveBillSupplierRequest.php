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
            $rules['sablon_id'] = [
                'required',
                'integer',
                Rule::exists('sablons', 'id')->whereNull('deleted_at'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'sablon_id.required' => 'Sablon wajib dipilih.',
            'sablon_id.exists'   => 'Sablon tidak ditemukan.',
            'date_bill.required' => 'Tanggal bill wajib diisi.',
            'date_bill.date'     => 'Format tanggal tidak valid.',
        ];
    }
}

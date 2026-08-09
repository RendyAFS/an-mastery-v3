<?php

namespace App\Http\Requests\Memo;

use Illuminate\Foundation\Http\FormRequest;

class SaveMemoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'name'        => ['required', 'string', 'max:255'],
            'nominal'     => ['nullable', 'numeric'],
            'date'        => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => __('memo.validation.employee_id.required'),
            'employee_id.exists'   => __('memo.validation.employee_id.exists'),
            'name.required'        => __('memo.validation.name.required'),
            'date.required'        => __('memo.validation.date.required'),
            'nominal.nullable'     => __('memo.validation.nominal.nullable'),
            'nominal.numeric'      => __('memo.validation.nominal.numeric'),
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id' => __('memo.fields.employee'),
            'name'        => __('memo.fields.name'),
            'nominal'     => __('memo.fields.nominal'),
            'date'        => __('memo.fields.date'),
        ];
    }
}

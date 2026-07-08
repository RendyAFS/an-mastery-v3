<?php

namespace App\Http\Requests\Presence;

use Illuminate\Foundation\Http\FormRequest;

class BulkGeneratePresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'week_of'        => 'required|date',
            'amount'         => 'required|integer|min:0',
            'employee_ids'   => 'required|array|min:1',
            'employee_ids.*' => 'integer|exists:employees,id',
        ];
    }

    public function messages(): array
    {
        return [
            'week_of.required'      => 'Week is required',
            'week_of.date'          => 'Week must be a valid date',
            'amount.required'       => 'Nominal per day is required',
            'amount.integer'        => 'Nominal per day must be an integer',
            'amount.min'            => 'Nominal per day must be at least 0',
            'employee_ids.required' => 'Select at least one employee',
            'employee_ids.min'      => 'Select at least one employee',
            'employee_ids.*.exists' => 'One or more selected employees are invalid',
        ];
    }
}

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
            'days'           => 'required|array|min:1',
            'days.*'         => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'employee_ids'   => 'required|array|min:1',
            'employee_ids.*' => 'integer|exists:employees,id',
        ];
    }

    public function messages(): array
    {
        return [
            'week_of.required'      => __('presence.validation.week_of.required'),
            'week_of.date'          => __('presence.validation.week_of.date'),
            'amount.required'       => __('presence.validation.amount.required'),
            'amount.integer'        => __('presence.validation.amount.integer'),
            'amount.min'            => __('presence.validation.amount.min'),
            'days.required'         => __('presence.validation.days.required'),
            'days.min'              => __('presence.validation.days.min'),
            'days.*.in'             => __('presence.validation.days.in'),
            'employee_ids.required' => __('presence.validation.employee_ids.required'),
            'employee_ids.min'      => __('presence.validation.employee_ids.min'),
            'employee_ids.*.exists' => __('presence.validation.employee_ids.exists'),
        ];
    }

    public function attributes(): array
    {
        return [
            'week_of'      => __('presence.form.week_of'),
            'amount'       => __('presence.form.amount'),
            'employee_ids' => __('presence.form.employees'),
        ];
    }
}

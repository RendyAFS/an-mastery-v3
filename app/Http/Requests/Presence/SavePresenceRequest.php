<?php

namespace App\Http\Requests\Presence;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SavePresenceRequest extends FormRequest
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
            'employee_id' => 'required|integer|exists:employees,id',
            'week_of'     => 'required|date',
            'monday'      => 'nullable|integer|min:0',
            'tuesday'     => 'nullable|integer|min:0',
            'wednesday'   => 'nullable|integer|min:0',
            'thursday'    => 'nullable|integer|min:0',
            'friday'      => 'nullable|integer|min:0',
            'saturday'    => 'nullable|integer|min:0',
            'sunday'      => 'nullable|integer|min:0',
            'notes'       => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => __('presence.validation.employee_id.required'),
            'employee_id.integer'  => __('presence.validation.employee_id.integer'),
            'employee_id.exists'   => __('presence.validation.employee_id.exists'),
            'week_of.required'     => __('presence.validation.week_of.required'),
            'week_of.date'         => __('presence.validation.week_of.date'),
            'monday.integer'       => __('presence.validation.days.integer'),
            'monday.min'           => __('presence.validation.days.min'),
            'tuesday.integer'      => __('presence.validation.days.integer'),
            'tuesday.min'          => __('presence.validation.days.min'),
            'wednesday.integer'    => __('presence.validation.days.integer'),
            'wednesday.min'        => __('presence.validation.days.min'),
            'thursday.integer'     => __('presence.validation.days.integer'),
            'thursday.min'         => __('presence.validation.days.min'),
            'friday.integer'       => __('presence.validation.days.integer'),
            'friday.min'           => __('presence.validation.days.min'),
            'saturday.integer'     => __('presence.validation.days.integer'),
            'saturday.min'         => __('presence.validation.days.min'),
            'sunday.integer'       => __('presence.validation.days.integer'),
            'sunday.min'           => __('presence.validation.days.min'),
            'notes.string'         => __('presence.validation.notes.string'),
            'notes.max'            => __('presence.validation.notes.max'),
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id' => __('presence.form.employee'),
            'week_of'     => __('presence.form.week_of'),
            'monday'      => __('presence.form.monday'),
            'tuesday'     => __('presence.form.tuesday'),
            'wednesday'   => __('presence.form.wednesday'),
            'thursday'    => __('presence.form.thursday'),
            'friday'      => __('presence.form.friday'),
            'saturday'    => __('presence.form.saturday'),
            'sunday'      => __('presence.form.sunday'),
            'notes'       => __('presence.form.notes'),
        ];
    }

    /**
     * Get the validated day values keyed by day name.
     *
     * @return array<string, int>
     */
    public function days(): array
    {
        return collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->mapWithKeys(fn($day) => [$day => (int) ($this->validated($day) ?? 0)])
            ->toArray();
    }
}

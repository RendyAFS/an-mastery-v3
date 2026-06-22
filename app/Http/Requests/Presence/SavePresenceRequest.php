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
            'employee_id.required' => 'Employee is required',
            'employee_id.integer'  => 'Employee must be an integer',
            'employee_id.exists'   => 'Employee not found',
            'week_of.required'     => 'Week of is required',
            'week_of.date'         => 'Week of must be a date',
            'monday.integer'       => 'Monday must be an integer',
            'monday.min'           => 'Monday must be at least 0',
            'tuesday.integer'      => 'Tuesday must be an integer',
            'tuesday.min'          => 'Tuesday must be at least 0',
            'wednesday.integer'    => 'Wednesday must be an integer',
            'wednesday.min'        => 'Wednesday must be at least 0',
            'thursday.integer'     => 'Thursday must be an integer',
            'thursday.min'         => 'Thursday must be at least 0',
            'friday.integer'       => 'Friday must be an integer',
            'friday.min'           => 'Friday must be at least 0',
            'saturday.integer'     => 'Saturday must be an integer',
            'saturday.min'         => 'Saturday must be at least 0',
            'sunday.integer'       => 'Sunday must be an integer',
            'sunday.min'           => 'Sunday must be at least 0',
            'notes.string'         => 'Notes must be a string',
            'notes.max'            => 'Notes must be less than 255 characters',
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

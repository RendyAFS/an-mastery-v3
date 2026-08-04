<?php
return [
    'description'              => 'Manage weekly employee presence data',
    'filter_week_label'        => 'Week Of',
    'generate_button'          => 'Generate Presence',
    'modal_title_prefix'       => 'Presence',
    'nominal_per_day'          => 'Nominal per day',
    'generate'                 => 'Generate',
    'notes'                    => 'Notes',
    'total'                    => 'Total',
    'employee'                 => 'Employee',
    'days'                     => [
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
        'sunday'    => 'Sunday',
    ],
    'bulk'                     => [
        'title'              => 'Generate Presence',
        'week_of'            => 'Week Of',
        'nominal_per_day'     => 'Nominal per day',
        'employees'          => 'Employees',
        'select_all'         => 'Select all',
        'no_employees_found' => 'No employees found',
        'select_week_error'  => 'Please select a week',
        'select_employee_error' => 'Please select at least one employee',
        'generated_success'  => 'Presence generated successfully',
    ],
    'updated_success'          => 'Presence updated successfully',
    'fetch_error'              => 'Failed to fetch presence data',
    'validation' => [
        'employee_id' => [
            'required' => 'Employee is required.',
            'integer'  => 'Employee is invalid.',
            'exists'   => 'Employee not found.',
        ],

        'week_of' => [
            'required' => 'Week is required.',
            'date'     => 'Week must be a valid date.',
        ],

        'days' => [
            'integer' => 'Attendance value must be an integer.',
            'min'     => 'Attendance value must be at least 0.',
        ],

        'amount' => [
            'required' => 'Daily amount is required.',
            'integer'  => 'Daily amount must be an integer.',
            'min'      => 'Daily amount must be at least 0.',
        ],

        'employee_ids' => [
            'required' => 'Please select at least one employee.',
            'min'      => 'Please select at least one employee.',
            'exists'   => 'One or more selected employees are invalid.',
        ],

        'notes' => [
            'string' => 'Notes must be a string.',
            'max'    => 'Notes may not be greater than 255 characters.',
        ],
    ],
];

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
        'title'                  => 'Generate Presence',
        'week_of'                => 'Week Of',
        'nominal_per_day'        => 'Nominal per day',
        'days'                   => 'Days',
        'select_all_days'        => 'Select all (Monday-Saturday)',
        'employees'              => 'Employees',
        'select_all'             => 'Select all',
        'no_employees_found'     => 'No employees found',
        'select_week_error'      => 'Please select a week',
        'select_day_error'       => 'Please select at least one day',
        'select_employee_error'  => 'Please select at least one employee',
        'generated_success'      => 'Presence generated successfully',
    ],
    'generated_success_count' => 'Presence generated successfully for :count employee(s)',
    'updated_success'         => 'Presence updated successfully',
    'created_success'         => 'Presence created successfully',
    'deleted_employee_error'  => 'Cannot update presence for a deleted employee',
    'fetch_error'             => 'Failed to fetch presence data',
    'form'                    => [
        'employee'  => 'Employee',
        'week_of'   => 'Week',
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
        'sunday'    => 'Sunday',
        'notes'     => 'Notes',
        'amount'    => 'Amount',
        'employees' => 'Employees',
    ],
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
            'required' => 'Please select at least one day.',
            'min'      => 'Please select at least one day.',
            'integer'  => 'Attendance value must be an integer.',
            'in'       => 'Selected day is invalid.',
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

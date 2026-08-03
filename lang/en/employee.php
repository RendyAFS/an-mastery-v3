<?php
return [
    'description'                    => 'Manage employee data',
    'fields'                         => [
        'name'    => 'Name',
        'address' => 'Address',
        'contact' => 'Contact',
        'notes'   => 'Notes',
    ],
    'toggle_active_confirm_title'   => 'Confirmation',
    'toggle_active_confirm_message' => 'Are you sure you want to change this employee status?',
    'toggle_active_success'         => 'Employee status updated successfully',
    'toggle_active_error'           => 'Failed to update employee status',
    'fetch_error'                   => 'Failed to fetch employee data',
];

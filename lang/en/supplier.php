<?php
return [
    'description'                    => 'Manage supplier data',
    'fields'                         => [
        'name'    => 'Name',
        'address' => 'Address',
        'contact' => 'Contact',
        'notes'   => 'Notes',
    ],
    'toggle_active_confirm_title'    => 'Confirmation',
    'toggle_active_confirm_message'  => 'Are you sure you want to change this supplier status?',
    'toggle_active_success'          => 'Supplier status updated successfully',
    'toggle_active_error'            => 'Failed to update supplier status',
    'fetch_error'                    => 'Failed to fetch supplier data',
    'validation' => [
        'name' => [
            'required' => 'Name is required.',
        ],
        'address' => [
            'required' => 'Address is required.',
        ],
        'contact' => [
            'string' => 'Contact must be a string.',
        ],
        'notes' => [
            'string' => 'Notes must be a string.',
        ],
    ],
    'cover_style' => [
        'color_from' => [
            'required' => 'Start color is required.',
            'regex'    => 'The start color must be a valid HEX color (e.g. #6366F1).',
        ],
        'color_to' => [
            'required' => 'End color is required.',
            'regex'    => 'The end color must be a valid HEX color (e.g. #4338CA).',
        ],
        'icon' => [
            'required' => 'Icon is required.',
            'in'       => 'The selected icon is invalid.',
        ],
        'pattern' => [
            'required' => 'Pattern is required.',
            'in'       => 'The selected pattern is invalid.',
        ],
    ],
];

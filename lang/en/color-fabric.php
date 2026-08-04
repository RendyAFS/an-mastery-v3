<?php
return [
    'description'  => 'Manage color fabric data',
    'fields'       => [
        'name'       => 'Name',
        'code_color' => 'Code Color',
        'notes'      => 'Notes',
    ],
    'fetch_error'  => 'Failed to fetch color fabric data',
    'validation' => [
        'name' => [
            'required' => 'Name is required.',
        ],

        'code_color' => [
            'required' => 'Color code is required.',
        ],

        'notes' => [
            'string' => 'Notes must be a string.',
        ],
    ],
];

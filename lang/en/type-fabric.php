<?php
return [
    'description'  => 'Manage type fabric data',
    'fields'       => [
        'name'  => 'Name',
        'notes' => 'Notes',
    ],
    'fetch_error'  => 'Failed to fetch type fabric data',
    'validation' => [
        'name' => [
            'required' => 'Name is required.',
            'string'   => 'Name must be a string.',
            'max'      => 'Name may not be greater than 255 characters.',
        ],

        'notes' => [
            'string' => 'Notes must be a string.',
        ],
    ],
];

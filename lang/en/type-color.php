<?php
return [
    'description' => 'Manage type color data',
    'fields'      => [
        'name'  => 'Name',
        'notes' => 'Notes',
    ],
    'name_suffix' => 'Color',
    'fetch_error' => 'Failed to fetch type color data',
    'validation' => [
        'name' => [
            'required' => 'Color count is required.',
            'integer'  => 'Color count must be an integer.',
            'min'      => 'Color count must be at least 1.',
        ],

        'notes' => [
            'string' => 'Notes must be a string.',
        ],
    ],
];

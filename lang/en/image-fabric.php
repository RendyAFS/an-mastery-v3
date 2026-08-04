<?php
return [
    'description' => 'Manage image fabric data',
    'fields'      => [
        'image' => 'Image',
        'name'  => 'Name',
        'notes' => 'Notes',
    ],
    'name_placeholder'  => 'Enter name',
    'notes_placeholder' => 'Enter notes',
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

<?php

return [
    'description' => 'Manage workshop / production branches',
    'fields'      => [
        'image'       => 'Workshop Image',
        'name'        => 'Workshop Name',
        'location'    => 'Location / Address',
        'is_active'   => 'Active Status',
        'users_count' => 'Total Users',
    ],
    'form' => [
        'name'         => 'Workshop Name',
        'location'     => 'Location / Address',
        'is_active'    => 'Active Status',
        'image'        => 'Image',
        'remove_image' => 'Remove Image',
    ],
    'name_placeholder'      => 'Enter workshop name',
    'location_placeholder'  => 'Enter location or address',
    'switch_workshop'       => 'Switch Workshop',
    'select_workshop'       => 'Select Workshop',
    'no_workshop'           => 'No Workshop Selected',
    'current_workshop'      => 'Current Workshop',
    'without_workshop'      => 'Without Workshop',
    'switched_successfully' => 'Workshop switched successfully.',
    'choose_workshop_desc'  => 'Select a workshop branch to work with.',
    'must_select_workshop'  => 'Please select a workshop first to proceed.',
    'cancel'                => 'Cancel',
    'validation' => [
        'name' => [
            'required' => 'Workshop name is required.',
            'string'   => 'Workshop name must be a string.',
            'max'      => 'Workshop name may not be greater than 255 characters.',
        ],
        'location' => [
            'string' => 'Location must be a string.',
        ],
    ],
];

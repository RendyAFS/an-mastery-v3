<?php
return [
    'description'          => 'Manage price employee data',
    'fields'               => [
        'type_fabric' => 'Type Fabric',
        'type_color'  => 'Type Color',
        'price'       => 'Price',
        'notes'       => 'Notes',
    ],
    'placeholders'         => [
        'type_fabric' => 'Choose Type Fabric',
        'type_color'  => 'Choose Type Color',
    ],
    'search_placeholders'  => [
        'type_fabric' => 'Search type fabric...',
        'type_color'  => 'Search type color...',
    ],
    'type_color_suffix'    => 'Color',
    'fetch_error'          => 'Failed to fetch price employee data',
    'validation' => [
        'type_fabric_id' => [
            'required' => 'Fabric type is required.',
            'exists'   => 'Please select a valid fabric type.',
        ],

        'type_color_id' => [
            'required' => 'Color type is required.',
            'exists'   => 'Please select a valid color type.',
        ],

        'price' => [
            'required' => 'Price is required.',
            'numeric'  => 'Price must be a number.',
        ],

        'notes' => [
            'string' => 'Notes must be a string.',
        ],
    ],
];

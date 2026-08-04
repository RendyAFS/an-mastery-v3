<?php
return [
    'description'          => 'Manage price supplier data',
    'fields'               => [
        'supplier'    => 'Supplier',
        'type_fabric' => 'Type Fabric',
        'type_color'  => 'Type Color',
        'price'       => 'Price',
        'notes'       => 'Notes',
    ],
    'placeholders'         => [
        'supplier'    => 'Choose Supplier',
        'type_fabric' => 'Choose Type Fabric',
        'type_color'  => 'Choose Type Color',
    ],
    'search_placeholders'  => [
        'supplier'    => 'Search supplier...',
        'type_fabric' => 'Search type fabric...',
        'type_color'  => 'Search type color...',
    ],
    'type_color_suffix'    => 'Color',
    'fetch_error'          => 'Failed to fetch price supplier data',
    'validation' => [
        'supplier_id' => [
            'required' => 'Supplier is required.',
            'exists'   => 'Please select a valid supplier.',
            'unique'   => 'This supplier, fabric type, and color type combination already exists.',
        ],

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

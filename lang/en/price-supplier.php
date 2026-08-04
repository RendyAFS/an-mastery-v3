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
    'validation'           => [
        'supplier_required'    => 'Supplier is required.',
        'supplier_exists'      => 'Please choose valid Supplier.',
        'combination_unique'   => 'Combination of Supplier, Type Fabric, and Type Color already exists.',
        'type_fabric_required' => 'Type Fabric is required.',
        'type_fabric_exists'   => 'Please choose valid Type Fabric.',
        'type_color_required'  => 'Type Color is required.',
        'type_color_exists'    => 'Please choose valid Type Color.',
        'price_required'       => 'Price is required.',
        'price_numeric'        => 'Price must be a number.',
        'notes_string'         => 'Notes must be a string.',
    ],
];

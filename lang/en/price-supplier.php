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
];

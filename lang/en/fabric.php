<?php
return [
    'description'  => 'Manage fabric data',
    'create_title' => 'Create Fabric',
    'edit_title'   => 'Edit Fabric',
    'filter'       => [
        'week_start' => 'From Week',
        'week_end'   => 'To Week',
        'reset'      => 'Reset Filter',
    ],
    'fields'             => [
        'supplier'    => 'Supplier',
        'code'        => 'Code',
        'type_seri'   => 'Type Seri',
        'stock_total' => 'Stock Total',
        'notes'       => 'Notes',
        'type_fabric' => 'Type Fabric',
        'total_stock' => 'Total Stock',
        'date_coming' => 'Date Coming',
    ],
    'placeholders'        => [
        'supplier'    => 'Choose Supplier',
        'type_fabric' => 'Choose Type Fabric',
        'color'       => 'Choose Color',
    ],
    'search_placeholders' => [
        'supplier'    => 'Search supplier...',
        'type_fabric' => 'Search type fabric...',
        'color'       => 'Search color...',
    ],
    'hints'              => [
        'total_stock' => 'Automatically calculated from Fabric Detail total stock',
        'code'        => 'Code is automatically generated from Supplier and timestamp',
    ],
    'clear_selection'    => 'Clear selection',
    'no_color_found'     => 'No color found',
    'detail'             => [
        'title'       => 'Fabric Detail (Color & Stock)',
        'add'         => 'Add Detail',
        'no'          => 'No',
        'color'       => 'Color Fabric',
        'stock'       => 'Stock',
        'notes'       => 'Notes',
        'action'      => 'Action',
    ],
    'incoming_label'               => 'Incoming',
    'no_sablon_yet'                => 'No sablon yet',
    'delete_confirm_message'       => 'Are you sure you want to delete this fabric? This action cannot be undone.',
    'restore_confirm_message'      => 'Restore this fabric?',
    'force_delete_confirm_message' => 'This will permanently delete the fabric. Continue?',
    'fetch_error'                  => 'Failed to fetch fabric data',
    'validation' => [
        'supplier_id' => [
            'required' => 'Supplier is required.',
            'exists'   => 'Supplier does not exist.',
        ],

        'type_fabric_id' => [
            'required' => 'Fabric type is required.',
            'exists'   => 'Fabric type does not exist.',
        ],

        'date_coming' => [
            'required' => 'Arrival date is required.',
            'date'     => 'Arrival date must be a valid date.',
        ],

        'seri' => [
            'required' => 'Serial number is required.',
            'integer'  => 'Serial number must be an integer.',
            'min'      => 'Serial number must be at least 1.',
        ],

        'notes' => [
            'max' => 'Notes may not be greater than 255 characters.',
        ],

        'fabric_details' => [
            'required' => 'At least one fabric detail is required.',

            'color_fabric_id' => [
                'required' => 'Color is required.',
                'exists'   => 'Selected color does not exist.',
            ],

            'stock' => [
                'required' => 'Stock is required.',
                'numeric'  => 'Stock must be a number.',
                'min'      => 'Stock must be at least 0.',
            ],
        ],
    ],
];

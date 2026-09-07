<?php
return [
    'description'  => 'Manage fabric data',
    'create_title' => 'Create Fabric',
    'edit_title'   => 'Edit Fabric',
    'filter'       => [
        'date_range' => 'Date Range',
        'reset'      => 'Reset',
    ],
    'fields'             => [
        'supplier'    => 'Konveksi',
        'code'        => 'Code',
        'type_seri'   => 'Type Seri',
        'stock_total' => 'Stock Total',
        'notes'       => 'Notes',
        'type_fabric' => 'Type Fabric',
        'total_stock' => 'Total Stock',
        'date_coming' => 'Date Coming',
    ],
    'placeholders'        => [
        'supplier'    => 'Choose Konveksi',
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
        'code'        => 'Code is automatically generated from Konveksi and timestamp',
    ],
    'clear_selection'        => 'Clear selection',
    'color_already_selected' => 'Color already selected',
    'no_color_found'         => 'No color found',
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
    'validation' => [
        'supplier_id' => [
            'required' => 'Konveksi is required.',
            'exists'   => 'Konveksi does not exist.',
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
                'distinct' => 'Color cannot be duplicated across details.',
            ],

            'stock' => [
                'required' => 'Stock is required.',
                'numeric'  => 'Stock must be a number.',
                'min'      => 'Stock must be at least 0.',
            ],
        ],
    ],
];

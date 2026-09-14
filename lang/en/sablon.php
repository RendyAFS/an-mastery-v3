<?php
return [
    'description'                  => 'Manage sablon data',
    'create_title'                 => 'Create Sablon',
    'edit_title'                   => 'Edit Sablon',
    'status_updated_success'       => 'Status updated successfully.',
    'delete_confirm_message'       => 'Are you sure you want to delete this Sablon?',
    'restore_confirm_message'      => 'Restore this Sablon?',
    'force_delete_confirm_message' => 'This will permanently delete the Sablon. Continue?',

    'filter'        => [
        'supplier'         => 'Supplier',
        'all_suppliers'    => 'All Suppliers',
        'type_fabric'      => 'Fabric Type',
        'all_type_fabrics' => 'All Fabric Types',
        'date_range'       => 'Date Range',
        'reset'            => 'Reset',
    ],

    'statuses'      => [
        'ON_PROGRESS' => 'On Progress',
        'DONE'        => 'Done',
        'DELIVERED'   => 'Delivered',
        'RETURNED'    => 'Returned',
    ],

    'main_info'     => [
        'title'  => 'Main Info',
        'fields' => [
            'supplier'             => 'Konveksi',
            'fabric'               => 'Fabric',
            'image_fabric'         => 'Image Fabric',
            'type_color'           => 'Type Color',
            'type_fabric'          => 'Type Fabric',
            'price_employee'       => 'Price Employee',
            'total_long_fabric'    => 'Total Long Fabric',
            'total_sablon'         => 'Total Sablon',
            'date_sablon'          => 'Date Sablon',
            'status'               => 'Status',
            'is_billed_in_advance' => 'Billed in Advance',
            'notes'                => 'Notes',
        ],
        'placeholders' => [
            'supplier'       => 'Choose Konveksi',
            'fabric'         => 'Choose Fabric',
            'image_fabric'   => 'Choose Image Fabric',
            'type_color'     => 'Choose Type Color',
            'type_fabric'    => 'Choose Type Fabric',
            'price_employee' => 'Choose Price Employee',
            'status'         => 'Choose Status',
        ],
        'search_placeholders' => [
            'supplier'       => 'Search supplier...',
            'fabric'         => 'Search fabric...',
            'image_fabric'   => 'Search image fabric...',
            'type_color'     => 'Search type color...',
            'type_fabric'    => 'Search type fabric...',
            'price_employee' => 'Search...',
        ],
        'hint_total_long_fabric'     => 'Automatically from total Fabric Detail',
    ],

    'fabric_detail' => [
        'title'                     => 'Fabric Detail (Color - Stock)',
        'no'                        => 'No',
        'fabric_detail_label'       => 'Fabric Detail (Color - Stock)',
        'long_fabric'               => 'Long Fabric',
        'action'                    => 'Action',
        'choose_fabric_detail'      => 'Choose Fabric Detail',
        'search_color_placeholder'  => 'Search color...',
        'no_fabric_detail_found'    => 'No fabric detail found for selected Fabric',
        'add_detail'                => 'Add Detail',
        'no_fabric_detail_yet'      => 'No fabric detail yet',
    ],

    'employee_detail' => [
        'title'                       => 'Employee Detail (Employee & Fee)',
        'employee_number'             => 'Employee #:number',
        'employee'                    => 'Employee',
        'layers'                      => 'Layers',
        'fee_auto'                    => 'Fee (auto)',
        'change_employee'             => 'Change Employee?',
        'employee_change'             => 'Employee Change',
        'is_bon'                      => 'Is Bon',
        'is_paid'                     => 'Is Paid',
        'choose_employee'             => 'Choose Employee',
        'search_employee_placeholder' => 'Search employee...',
        'no_employee_found'           => 'No employee found',
        'no_employee_detail_yet'      => 'No employee detail yet',
        'add_employee'                => 'Add Employee',
        'locked_badge'                => 'Locked',
        'settlement_badge'            => 'Settlement Bon',
        'bon_badge'                   => 'Bon',
        'additional_fee_label'        => 'Additional Fee (auto-linked to Salary)',
        'add_fee'                     => 'Add Fee',
        'no_additional_fee_yet'       => 'No additional fee yet',
        'nominal'                     => 'Nominal',
        'notes'                       => 'Notes',
        'notes_placeholder'           => 'e.g. Bon Kain',
        'quick_notes'                 => [
            'Plus kain ',
            'Minus kain ',
        ],
        'manage_salary_fee'           => 'Manage Salary Fee',
        'salary_fee_modal_title'      => 'Weekly Salary Additional Fee',
        'pick_date_first'             => 'Please select the printing date first',
    ],

    'status_modal'  => [
        'title'  => 'Update Status',
        'cancel' => 'Cancel',
        'save'   => 'Save',
    ],

    'card'          => [
        'date'                => 'Date',
        'total_sablon'        => 'Total Sablon',
        'long_fabric'         => 'Long Fabric',
        'type_color'          => 'Type Color',
        'type_color_suffix'   => 'Color',
        'fabric_details'      => 'Fabric Details',
        'employee_details'    => 'Employee Details',
        'layer_suffix'        => 'Layer',
        'bon'                 => 'Bon',
        'settlement_bon'      => 'Bon Settlement',
        'paid'                => 'Paid',
        'change_to'           => 'Change to :name',
        'deleted'             => 'Deleted',
        'restore'             => 'Restore',
        'delete'              => 'Delete',
    ],


    'validation' => [
        'supplier_id' => [
            'required' => 'Konveksi is required.',
            'exists'   => 'The selected supplier is invalid.',
        ],

        'fabric_id' => [
            'required' => 'Fabric is required.',
            'exists'   => 'The selected fabric is invalid.',
        ],

        'image_fabric_id' => [
            'required' => 'Fabric image is required.',
            'exists'   => 'The selected fabric image is invalid.',
        ],

        'type_color_id' => [
            'required' => 'Color type is required.',
            'exists'   => 'The selected color type is invalid.',
        ],

        'type_fabric_id' => [
            'required' => 'Fabric type is required.',
            'exists'   => 'The selected fabric type is invalid.',
        ],

        'price_employee_id' => [
            'required' => 'Employee price is required.',
            'exists'   => 'The selected employee price is invalid.',
        ],

        'total_long_fabric' => [
            'numeric' => 'Total fabric length must be a number.',
            'min'     => 'Total fabric length must be at least 0.',
        ],

        'total_sablon' => [
            'numeric' => 'Total printing quantity must be a number.',
            'min'     => 'Total printing quantity must be at least 0.',
        ],

        'date_sablon' => [
            'required' => 'Printing date is required.',
            'date'     => 'Printing date must be a valid date.',
        ],

        'status' => [
            'required' => 'Status is required.',
            'in'       => 'The selected status is invalid.',
        ],

        'notes' => [
            'string' => 'Notes must be a string.',
            'max'    => 'Notes may not be greater than 255 characters.',
        ],

        'fabric_details' => [
            'required' => 'At least one fabric detail is required.',
            'array'    => 'Invalid fabric details format.',
            'min'      => 'At least one fabric detail is required.',

            'fabric_detail_id' => [
                'required' => 'Fabric detail is required.',
                'exists'   => 'The selected fabric detail is invalid.',
            ],

            'color_fabric_id' => [
                'required' => 'Fabric color is required.',
                'exists'   => 'The selected fabric color is invalid.',
            ],

            'long_fabric' => [
                'numeric' => 'Fabric length must be a number.',
                'min'     => 'Fabric length must be at least 0.',
            ],
        ],

        'employee_details' => [
            'array' => 'Invalid employee details format.',

            'employee_id' => [
                'required_with' => 'Employee is required.',
                'exists'        => 'The selected employee is invalid.',
            ],

            'layers' => [
                'integer' => 'Layers must be an integer.',
                'min'     => 'Layers must be at least 0.',
            ],

            'fee' => [
                'numeric' => 'Fee must be a number.',
                'min'     => 'Fee must be at least 0.',
            ],

            'employee_change_id' => [
                'exists'    => 'The selected replacement employee is invalid.',
                'different' => 'The replacement employee must be different from the primary employee.',
            ],

            'notes' => [
                'string' => 'Notes must be a string.',
                'max'    => 'Notes may not be greater than 255 characters.',
            ],
        ],
    ],
    'settlement_notes' => 'Bon settlement from detail #:id',

    'bulk' => [
        'select_mode' => 'Choose',
        'cancel_select' => 'Cancel Selection',
        'selected_count' => ':count selected',
        'select_status' => 'Select new status',
        'apply' => 'Apply',
        'cancel' => 'Cancel',
        'status_required' => 'Please select a status first',
        'confirm_title' => 'Bulk Status Update',
        'confirm_message' => 'Change the status of :count selected screen prints to ":status"?',
    ],
    'bulk_status_updated_success' => ':count screen prints successfully updated',
    'restore_failed_fabric_deleted' => 'Cannot restore screen print because the related fabric was not found or has been deleted.',
    'restore_failed_stock_empty' => 'Cannot restore screen print because fabric (:fabric) stock is empty.',
    'restore_failed_detail_not_found' => 'Cannot restore screen print because fabric color detail was not found.',
    'restore_failed_insufficient_stock' => 'Cannot restore screen print because fabric (:fabric) stock for color :color is insufficient (available: :available, needed: :needed).',
];

<?php

return [
    'description' => 'Manage employee memo records',
    'filter' => [
        'week_start' => 'From Week',
        'week_end'   => 'To Week',
        'reset'      => 'Reset',
    ],
    'fields' => [
        'employee'     => 'Employee',
        'items_count'  => 'Items Count',
        'total'        => 'Total Amount',
        'status'       => 'Status',
        'date'         => 'Date',
        'item_details' => 'Item Details',
        'item_name'    => 'Item Name',
        'nominal'      => 'Nominal',
        'add_item'     => 'Add Item',
        'is_paid'      => 'Paid',
    ],
    'placeholders' => [
        'employee'        => 'Choose Employee...',
        'search_employee' => 'Search employee...',
        'item_name'       => 'Expense item name...',
    ],
    'status' => [
        'paid'   => 'Paid',
        'unpaid' => 'Unpaid',
    ],
    'validation' => [
        'employee_id' => [
            'required' => 'Employee is required.',
            'exists'   => 'Selected employee is invalid.',
        ],
        'data' => [
            'required' => 'Memo item details are required.',
            'min'      => 'At least one memo item is required.',
        ],
        'data_name' => [
            'required' => 'Item name is required.',
        ],
        'data_date' => [
            'required' => 'Item date is required.',
        ],
    ],
];

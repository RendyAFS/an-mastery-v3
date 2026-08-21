<?php

return [
    'description' => 'Weekly employee fee recap',
    'filter' => [
        'date_range' => 'Date Range',
        'reset' => 'Reset',
        'all' => 'All',
    ],
    'sync' => [
        'button'              => 'Sync Data',
        'loading'             => 'Syncing...',
        'select_week_warning' => 'Please select a week range first',
        'synced_success'      => 'Synced :count employee salary records.',
        'confirm_title'       => 'Sync Salary Data?',
        'confirm_message'     => 'This will recalculate & lock in eligible sablon, bon, and memo data into employee salaries for the selected week. Salary records already marked "Paid" will automatically reopen to "Pending" if new data comes in.',
    ],
    'status' => [
        'PENDING' => 'Pending',
        'PAID' => 'Paid',
    ],
    'modal' => [
        'title' => 'Salary Employee',
        'status_label' => 'Status',
        'status_placeholder' => 'Choose Status',
        'additional_fee_label' => 'Additional Fee',
        'add_fee' => 'Add Fee',
        'nominal' => 'Nominal',
        'notes' => 'Notes',
        'notes_placeholder' => 'Notes',
        'locked_hint' => 'Cannot edit paid salary locked change to Unpaid first',
        'previous_week_fee_title' => 'Previous Unpaid Salary',
    ],
    'card' => [
        'no_sablon_data' => 'No sablon data yet',
        'additional_fee' => 'Additional Fee',
        'presence' => 'Presence',
        'total' => 'Total',
        'manage' => 'Manage',
        'bon_label' => 'Bon',
        'bon_advance_label' => 'Bon Advanced',
        'bon_settlement_label' => 'Bon Settlement',
        'previous_week_fee' => 'Salary :date',
    ],
];

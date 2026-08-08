<?php

return [
    'description' => 'Weekly employee fee recap',
    'filter' => [
        'week_start' => 'From Week',
        'week_end' => 'To Week',
        'reset' => 'Reset',
        'all' => 'All',
    ],
    'sync' => [
        'button' => 'Sync Data',
        'loading' => 'Syncing...',
        'select_week_warning' => 'Please select a week range first',
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
    ],
    'card' => [
        'no_sablon_data' => 'No sablon data yet',
        'additional_fee' => 'Additional Fee',
        'presence' => 'Presence',
        'total' => 'Total',
        'manage' => 'Manage',
    ],
];

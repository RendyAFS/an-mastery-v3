<?php

return [
    'description' => 'Manage employee salary bonus tiers',
    'fields'      => [
        'min'   => 'Min Total Sablon',
        'bonus' => 'Bonus Amount',
        'notes' => 'Notes',
    ],
    'placeholders' => [
        'min'   => 'Enter min total sablon',
        'bonus' => 'Enter bonus amount',
        'notes' => 'Enter notes',
    ],
    'fetch_error' => 'Failed to fetch bonus data',
    'validation'  => [
        'min' => [
            'required' => 'Min total sablon is required.',
            'numeric'  => 'Min total sablon must be a number.',
            'min'      => 'Min total sablon cannot be less than 0.',
        ],
        'bonus' => [
            'required' => 'Bonus amount is required.',
            'numeric'  => 'Bonus amount must be a number.',
            'min'      => 'Bonus amount cannot be less than 0.',
        ],
        'notes' => [
            'string' => 'Notes must be text.',
        ],
    ],
];

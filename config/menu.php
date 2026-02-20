<?php

return [
    [
        'name'        => 'Dashboard',
        'icon'        => 'home',
        'url'         => '/dashboard',
        'permissions' => ['view'],
    ],

    // Access Management
    [
        'name' => 'Access Management',
        'icon' => 'shield',
        'url'  => '#access-management',
        'children' => [
            [
                'name' => 'Users',
                'url'  => '/users',
            ],
            [
                'name' => 'Roles',
                'url'  => '/roles',
            ],
        ],
    ],

    // Master Data
    [
        'name' => 'Master Data',
        'icon' => 'server',
        'url'  => '#master-data',
        'children' => [
            [
                'name' => 'Suppliers',
                'url'  => '/suppliers',
            ],
        ],
    ],
];

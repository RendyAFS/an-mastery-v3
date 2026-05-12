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
            [
                'name' => 'Employees',
                'url'  => '/employees',
            ],
            [
                'name' => 'Image Fabrics',
                'url'  => '/image-fabrics',
            ],
            [
                'name' => 'Color Fabrics',
                'url'  => '/color-fabrics',
            ],
            [
                'name' => 'Type Fabrics',
                'url'  => '/type-fabrics',
            ],
        ],
    ],
];

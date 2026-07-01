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

    // People
    [
        'name' => 'People',
        'icon' => 'users',
        'url'  => '#people',
        'children' => [
            [
                'name' => 'Suppliers',
                'url'  => '/suppliers',
            ],
            [
                'name' => 'Employees',
                'url'  => '/employees',
            ],
        ],
    ],

    // Fabric Attribute
    [
        'name' => 'Fabric Attribute',
        'icon' => 'layers',
        'url'  => '#fabric-attribute',
        'children' => [
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
            [
                'name' => 'Type Colors',
                'url'  => '/type-colors',
            ],
        ],
    ],

    // Pricing
    [
        'name' => 'Pricing',
        'icon' => 'dollar-sign',
        'url'  => '#pricing',
        'children' => [
            [
                'name' => 'Price Supplier',
                'url'  => '/price-suppliers',
            ],
            [
                'name' => 'Price Employee',
                'url'  => '/price-employees',
            ],
        ],
    ],

    // Employee Presence
    [
        'name' => 'Employee Presence',
        'icon' => 'calendar-check-2',
        'url'  => '/presences',
    ],

    // Inventory Fabric
    [
        'name' => 'Inventory Fabric',
        'icon' => 'package',
        'url'  => '/fabrics',
    ],

    // Sablon
    [
        'name' => 'Sablon',
        'icon' => 'paintbrush',
        'url'  => '/sablons',
    ],

    // Bill Supplier
    [
        'name' => 'Bill Supplier',
        'icon' => 'receipt-text',
        'url'  => '/bill-suppliers',
    ],
];

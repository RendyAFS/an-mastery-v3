<?php

return [
    [
        'name'        => 'Dashboard',
        'icon'        => 'home',
        'url'         => 'dashboard',
        'permissions' => ['view'],
    ],

    [
        'name' => 'Access Management',
        'icon' => 'shield',
        'url'  => '#access-management',
        'children' => [
            [
                'name' => 'Roles',
                'url'  => 'roles',
            ],
        ],
    ],
];

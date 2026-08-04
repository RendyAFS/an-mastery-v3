<?php
return [
    'description'                   => 'Manage user data',
    'fields'                        => [
        'name'                   => 'Name',
        'email'                  => 'Email',
        'password'               => 'Password',
        'password_confirmation'  => 'Confirm Password',
        'role'                   => 'Role',
        'roles'                  => 'Roles',
    ],
    'password_placeholder'          => 'Enter password',
    'password_confirmation_placeholder' => 'Confirm password',
    'password_hint'                 => 'Leave blank if you don\'t want to change the password',
    'role_placeholder'              => 'Choose Role',
    'role_search_placeholder'       => 'Search role...',
    'toggle_active_confirm_title'   => 'Confirmation',
    'toggle_active_confirm_message' => 'Are you sure you want to change this user status?',
    'toggle_active_success'         => 'User status updated successfully',
    'toggle_active_error'           => 'Failed to update user status',
    'validation' => [
        'name' => [
            'required' => 'Name is required.',
            'string'   => 'Name must be a string.',
            'unique'   => 'Name has already been taken.',
        ],

        'email' => [
            'required' => 'Email is required.',
            'email'    => 'Please enter a valid email address.',
            'unique'   => 'Email has already been taken.',
        ],

        'password' => [
            'required'  => 'Password is required.',
            'confirmed' => 'Password confirmation does not match.',
        ],

        'is_active' => [
            'boolean' => 'Status is invalid.',
        ],

        'roles' => [
            'exists' => 'The selected role is invalid.',
        ],
    ],
];

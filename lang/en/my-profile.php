<?php

return [

    'title' => [
        'profile_information' => 'Profile Information',
        'change_password'     => 'Change Password',
    ],

    'form' => [
        'avatar'                => 'Profile Photo',
        'name'                  => 'Name',
        'email'                 => 'Email',
        'password'              => 'New Password',
        'password_confirmation' => 'Confirm Password',

        'placeholder_name'      => 'Enter your name',
        'placeholder_email'     => 'Enter your email',
        'placeholder_password'  => 'Enter password',
        'placeholder_password_confirmation'
        => 'Confirm password',
    ],

    'button' => [
        'save_profile'      => 'Save Profile',
        'saving_profile'    => 'Saving...',
        'update_password'   => 'Update Password',
        'updating_password' => 'Updating...',
    ],

    'message' => [
        'profile_updated'  => 'Profile updated successfully.',
        'password_updated' => 'Password updated successfully.',
    ],

    'validation' => [
        'name' => [
            'required' => 'Name is required.',
            'string'   => 'Name must be a string.',
            'max'      => 'Name may not be greater than 255 characters.',
        ],

        'email' => [
            'required' => 'Email is required.',
            'email'    => 'Please enter a valid email address.',
            'unique'   => 'This email has already been taken.',
        ],

        'password' => [
            'required'  => 'Password is required.',
            'string'    => 'Password must be a string.',
            'confirmed' => 'Password confirmation does not match.',
            'min'       => 'Password must be at least 8 characters.',
            'letters'   => 'Password must contain at least one letter.',
            'numbers'   => 'Password must contain at least one number.',
        ],
    ],

];

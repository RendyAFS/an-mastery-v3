<?php
return [
    'back_to_home' => 'Back to Home',
    'default_title'   => 'Error',
    'default_message' => 'Something went wrong.',

    'codes' => [
        '401' => [
            'title'   => 'Unauthorized',
            'message' => 'You need to sign in to view this.',
        ],
        '403' => [
            'title'   => 'Forbidden',
            'message' => "You don't have access to this page.",
        ],
        '404' => [
            'title'   => 'Not Found',
            'message' => "We couldn't find what you're looking for.",
        ],
        '419' => [
            'title'   => 'Page Expired',
            'message' => 'Your session timed out. Please refresh and try again.',
        ],
        '429' => [
            'title'   => 'Too Many Requests',
            'message' => 'Too many requests. Please slow down and try again shortly.',
        ],
        '500' => [
            'title'   => 'Server Error',
            'message' => 'Something went wrong on our end.',
        ],
        '503' => [
            'title'   => 'Maintenance',
            'message' => "We're making some updates. Back shortly.",
        ],
    ],
];

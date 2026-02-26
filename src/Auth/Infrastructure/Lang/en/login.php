<?php

return [
    'missing_header_platform_type' => [
        'title' => 'Required header',
        'description' => 'You must send the platform-type header.',
    ],
    'invalid_header_platform_type' => [
        'title' => 'Invalid header',
        'description' => 'The platform-type header is invalid.',
    ],
    'invalid_header_token_ttl_hours' => [
        'title' => 'Invalid token lifetime',
        'description' => 'token-ttl-hours only applies to platform-type 4 and must be between 8 and 24 hours.',
    ],
    'missing_header_latitude' => [
        'title' => 'Required header',
        'description' => 'You must send the X-Latitude header.',
    ],
    'invalid_header_latitude' => [
        'title' => 'Invalid header',
        'description' => 'The X-Latitude header is invalid.',
    ],
    'missing_header_longitude' => [
        'title' => 'Required header',
        'description' => 'You must send the X-Longitude header.',
    ],
    'invalid_header_longitude' => [
        'title' => 'Invalid header',
        'description' => 'The X-Longitude header is invalid.',
    ],
    'temporarily_locked' => [
        'title' => 'User locked',
        'description' => 'The account is temporarily locked.',
    ],
    'last_attempt_before_lockout' => [
        'title' => 'Invalid attempt',
        'description' => 'Invalid credentials. You have :attempt attempts left.',
    ],
    'invalid_credentials' => [
        'title' => 'Invalid credentials',
        'description' => 'The username or password is invalid.',
    ],
];

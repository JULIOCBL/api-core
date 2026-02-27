<?php

return [
    'invalid_credentials' => [
        'title' => 'Invalid credentials',
        'description' => 'The username or password is invalid.',
    ],
    'temporarily_locked' => [
        'title' => 'User locked',
        'description' => 'The account is temporarily locked.',
    ],
    'last_attempt_before_lockout' => [
        'title' => 'Invalid attempt',
        'description' => 'Invalid credentials. You have :attempt attempts left.',
    ],
    'login_success' => [
        'title' => 'Session started',
        'description' => 'Login completed successfully.',
    ],
    'invalid_refresh_token' => [
        'title' => 'Invalid refresh token',
        'description' => 'The refresh token is invalid or expired.',
    ],
    'refresh_success' => [
        'title' => 'Token refreshed',
        'description' => 'Session refreshed successfully.',
    ],
    'missing_access_token' => [
        'title' => 'Token required',
        'description' => 'You must send an access token to logout.',
    ],
    'missing_bearer_token' => [
        'title' => 'Token required',
        'description' => 'Missing token in Authorization header.',
    ],
    'invalid_access_token' => [
        'title' => 'Invalid access token',
        'description' => 'The access token is invalid or expired.',
    ],
    'missing_company_context' => [
        'title' => 'Company required',
        'description' => 'This user requires an active company to execute the operation.',
    ],
    'invalid_company_context' => [
        'title' => 'Invalid company',
        'description' => 'The provided company is invalid or does not exist.',
    ],
    'logout_success' => [
        'title' => 'Session closed',
        'description' => 'Session closed successfully.',
    ],
];

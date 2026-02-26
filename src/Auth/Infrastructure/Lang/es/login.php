<?php

return [
    'missing_header_platform_type' => [
        'title' => 'Encabezado requerido',
        'description' => 'Debes enviar el encabezado platform-type.',
    ],
    'invalid_header_platform_type' => [
        'title' => 'Encabezado inválido',
        'description' => 'El encabezado platform-type es inválido.',
    ],
    'invalid_header_token_ttl_hours' => [
        'title' => 'Tiempo de vida inválido',
        'description' => 'token-ttl-hours solo aplica para platform-type 4 y debe estar entre 8 y 24 horas.',
    ],
    'missing_header_latitude' => [
        'title' => 'Encabezado requerido',
        'description' => 'Debes enviar el encabezado X-Latitude.',
    ],
    'invalid_header_latitude' => [
        'title' => 'Encabezado inválido',
        'description' => 'El encabezado X-Latitude es inválido.',
    ],
    'missing_header_longitude' => [
        'title' => 'Encabezado requerido',
        'description' => 'Debes enviar el encabezado X-Longitude.',
    ],
    'invalid_header_longitude' => [
        'title' => 'Encabezado inválido',
        'description' => 'El encabezado X-Longitude es inválido.',
    ],
    'temporarily_locked' => [
        'title' => 'Usuario bloqueado',
        'description' => 'La cuenta está bloqueada temporalmente.',
    ],
    'last_attempt_before_lockout' => [
        'title' => 'Intento inválido',
        'description' => 'Credenciales inválidas. Te quedan :attempt intentos.',
    ],
    'invalid_credentials' => [
        'title' => 'Credenciales inválidas',
        'description' => 'El usuario o la contraseña no son válidos.',
    ],
];

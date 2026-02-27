<?php

return [
    'invalid_credentials' => [
        'title' => 'Credenciales inválidas',
        'description' => 'El usuario o la contraseña no son válidos.',
    ],
    'temporarily_locked' => [
        'title' => 'Usuario bloqueado',
        'description' => 'La cuenta está bloqueada temporalmente.',
    ],
    'last_attempt_before_lockout' => [
        'title' => 'Intento inválido',
        'description' => 'Credenciales inválidas. Te quedan :attempt intentos.',
    ],
    'login_success' => [
        'title' => 'Sesión iniciada',
        'description' => 'El inicio de sesión se realizó correctamente.',
    ],
    'invalid_refresh_token' => [
        'title' => 'Refresh token inválido',
        'description' => 'El refresh token no es válido o ya expiró.',
    ],
    'refresh_success' => [
        'title' => 'Token renovado',
        'description' => 'La sesión fue renovada correctamente.',
    ],
    'missing_access_token' => [
        'title' => 'Token requerido',
        'description' => 'Debes enviar un access token para cerrar sesión.',
    ],
    'missing_bearer_token' => [
        'title' => 'Token requerido',
        'description' => 'Token faltante en el encabezado Authorization.',
    ],
    'invalid_access_token' => [
        'title' => 'Access token inválido',
        'description' => 'El access token no es válido o ya expiró.',
    ],
    'missing_company_context' => [
        'title' => 'Compañía requerida',
        'description' => 'Este usuario requiere una compañía activa para ejecutar la operación.',
    ],
    'invalid_company_context' => [
        'title' => 'Compañía inválida',
        'description' => 'La compañía enviada es inválida o no existe.',
    ],
    'logout_success' => [
        'title' => 'Sesión cerrada',
        'description' => 'La sesión se cerró correctamente.',
    ],
];

<?php

namespace Src\Shared\Infrastructure\Errors;

/**
 * Catálogo de códigos internos de error para respuestas JSON.
 */
final class ErrorCodes
{
    // DESCONOCIDO
    public const UNKNOWN_1000 = 1000;

    // AUTH: LOGIN
    public const AUTH_LOGIN_ERROR_1001 = 1001;
    public const AUTH_INACTIVE_USER_1002 = 1002;
    public const AUTH_UNAUTHORIZED_1003 = 1003;
    public const AUTH_INVALID_CREDENTIALS_1004 = 1004;
    public const AUTH_USER_LOCKED_TEMPORARILY_1005 = 1005;
    public const AUTH_LOGIN_ATTEMPTS_EXCEEDED_1006 = 1006;
    public const AUTH_MISSING_PLATFORM_HEADER_1007 = 1007;
    public const AUTH_INVALID_PLATFORM_HEADER_1008 = 1008;

    // AUTH: TOKEN / SESION
    // Falta access token en body/header al cerrar sesión.
    public const AUTH_MISSING_ACCESS_TOKEN_1009 = 1009;
    // Access token inválido, expirado o no vigente en BD.
    public const AUTH_INVALID_ACCESS_TOKEN_1010 = 1010;
    // Refresh token inválido, expirado o no vigente en BD.
    public const AUTH_INVALID_REFRESH_TOKEN_1011 = 1011;
    // Falta encabezado Authorization Bearer en rutas protegidas.
    public const AUTH_MISSING_BEARER_TOKEN_1012 = 1012;
    // Usuario requiere compañía activa y no fue resuelta.
    public const AUTH_MISSING_COMPANY_CONTEXT_1013 = 1013;
    // Compañía enviada inválida o inexistente.
    public const AUTH_INVALID_COMPANY_CONTEXT_1014 = 1014;
    // request()->user() sin contexto esperado para helper de auth.
    public const AUTH_MISSING_AUTH_USER_CONTEXT_1015 = 1015;

    // AUTH: HEADERS DE LOGIN
    // Header token-ttl-hours inválido para login.
    public const AUTH_INVALID_TOKEN_TTL_HOURS_1016 = 1016;
    // Falta header X-Latitude.
    public const AUTH_MISSING_LATITUDE_HEADER_1017 = 1017;
    // Falta header X-Longitude.
    public const AUTH_MISSING_LONGITUDE_HEADER_1018 = 1018;
    // Header X-Latitude fuera de rango o inválido.
    public const AUTH_INVALID_LATITUDE_HEADER_1019 = 1019;
    // Header X-Longitude fuera de rango o inválido.
    public const AUTH_INVALID_LONGITUDE_HEADER_1020 = 1020;
    // No se pudo decodificar o validar criptográficamente el access token.
    public const AUTH_ACCESS_TOKEN_DECODE_ERROR_1021 = 1021;
    // Payload del access token inválido (sin jwtid/platform-type).
    public const AUTH_ACCESS_TOKEN_PAYLOAD_INVALID_1022 = 1022;
    // Registro de access token no encontrado o no vigente en BD.
    public const AUTH_ACCESS_TOKEN_RECORD_NOT_FOUND_1023 = 1023;
    // El platform-type del token no coincide con el registro persistido.
    public const AUTH_ACCESS_TOKEN_PLATFORM_MISMATCH_1024 = 1024;
    // Access token expirado o fuera de ventana de validez.
    public const AUTH_ACCESS_TOKEN_EXPIRED_1025 = 1025;
    // Estructura de abilities inválida en token persistido.
    public const AUTH_ACCESS_TOKEN_ABILITIES_INVALID_1026 = 1026;
    // Usuario autenticado no encontrado o inactivo.
    public const AUTH_ACCESS_TOKEN_USER_INVALID_1027 = 1027;

    // REGISTER
    public const REGISTER_EMAIL_EXISTS_1101 = 1101;
    public const REGISTER_USERNAME_TAKEN_1102 = 1102;

    // GENERAL
    public const GENERAL_UNKNOWN_9000 = 9000;
    // Request malformado o inválido a nivel general (HTTP 400).
    public const GENERAL_BAD_REQUEST_9001 = 9001;

}

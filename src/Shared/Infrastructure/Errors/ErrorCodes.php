<?php

namespace Src\Shared\Infrastructure\Errors;

/**
 * Catálogo de códigos internos de error para respuestas JSON.
 */
final class ErrorCodes
{
    public const UNKNOWN_1000 = 1000;

    public const AUTH_LOGIN_ERROR_1001 = 1001;
    public const AUTH_INACTIVE_USER_1002 = 1002;
    public const AUTH_UNAUTHORIZED_1003 = 1003;
    public const AUTH_INVALID_CREDENTIALS_1004 = 1004;
    public const AUTH_USER_LOCKED_TEMPORARILY_1005 = 1005;
    public const AUTH_LOGIN_ATTEMPTS_EXCEEDED_1006 = 1006;
    public const AUTH_MISSING_PLATFORM_HEADER_1007 = 1007;
    public const AUTH_INVALID_PLATFORM_HEADER_1008 = 1008;

    public const REGISTER_EMAIL_EXISTS_1101 = 1101;
    public const REGISTER_USERNAME_TAKEN_1102 = 1102;

    public const GENERAL_UNKNOWN_9000 = 9000;

}

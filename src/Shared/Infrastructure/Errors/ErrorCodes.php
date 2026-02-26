<?php

namespace Src\Shared\Infrastructure\Errors;

/**
 * Catálogo de códigos internos de error para respuestas JSON.
 */
final class ErrorCodes
{
    public const UNKNOWN = 1000;

    public const AUTH_LOGIN_ERROR = 1001;
    public const AUTH_INACTIVE_USER = 1002;
    public const AUTH_UNAUTHORIZED = 1003;
    public const AUTH_INVALID_CREDENTIALS = 1004;
    public const AUTH_USER_LOCKED_TEMPORARILY = 1005;
    public const AUTH_LOGIN_ATTEMPTS_EXCEEDED = 1006;
    public const AUTH_MISSING_PLATFORM_HEADER = 1007;
    public const AUTH_INVALID_PLATFORM_HEADER = 1008;

    public const REGISTER_EMAIL_EXISTS = 1101;
    public const REGISTER_USERNAME_TAKEN = 1102;

    public const GENERAL_UNKNOWN = 9000;
}

<?php

namespace Src\Core\Infrastructure\Support\Helpers;

use Psr\Log\LogLevel;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Helper para obtener el usuario autenticado del request actual.
 */
final class UserHelper
{
    /**
     * @return User
     */
    public static function user(): User
    {
        $auth_user = request()->user();
        if ($auth_user instanceof User) {
            return $auth_user;
        }

        throw new JsonException(
            LogLevel::WARNING,
            __('auth::session.invalid_access_token.title'),
            __('auth::session.invalid_access_token.description'),
            HttpResponse::HTTP_UNAUTHORIZED,
            '',
            ErrorCodes::AUTH_MISSING_AUTH_USER_CONTEXT_1015
        );
    }

    /**
     * @return string
     */
    public static function id(): string
    {
        return (string) self::user()->id;
    }
}

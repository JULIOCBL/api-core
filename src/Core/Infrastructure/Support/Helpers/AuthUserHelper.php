<?php

namespace Src\Core\Infrastructure\Support\Helpers;

use Psr\Log\LogLevel;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\UserType;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Helper para verificar tipo de usuario autenticado en request actual.
 */
final class AuthUserHelper
{
    /**
     * @return bool
     */
    public static function isRoot(): bool
    {
        return self::getUserTypeId() === UserType::ROOT;
    }

    /**
     * @return bool
     */
    public static function isSuperAdmin(): bool
    {
        return self::getUserTypeId() === UserType::SUPER_USUARIO;
    }

    /**
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return self::getUserTypeId() === UserType::ADMINISTRATOR;
    }

    /**
     * @return bool
     */
    public static function isUser(): bool
    {
        return self::getUserTypeId() === UserType::USER;
    }

    /**
     * @return int|null
     */
    public static function getUserTypeId(): int
    {
        $auth_user = UserHelper::user();
        if (is_object($auth_user) && isset($auth_user->user_type_id) && is_numeric($auth_user->user_type_id)) {
            return (int) $auth_user->user_type_id;
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
}

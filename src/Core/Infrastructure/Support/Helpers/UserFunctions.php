<?php

use Src\Core\Infrastructure\Support\Helpers\UserHelper;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;

if (!function_exists('user')) {
    /**
     * Obtiene el usuario autenticado del contexto actual.
     *
     * @return User
     */
    function user(): User
    {
        return UserHelper::user();
    }
}

if (!function_exists('user_id')) {
    /**
     * Obtiene el id del usuario autenticado actual.
     *
     * @return string
     */
    function user_id(): string
    {
        return UserHelper::id();
    }
}

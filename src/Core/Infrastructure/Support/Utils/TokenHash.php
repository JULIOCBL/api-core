<?php

namespace Src\Core\Infrastructure\Support\Utils;

/**
 * Utilidad para generar hash determinístico de tokens.
 */
class TokenHash
{
    /**
     * @param string $token
     * @return string
     */
    public static function make(string $token): string
    {
        return hash('sha256', $token);
    }
}

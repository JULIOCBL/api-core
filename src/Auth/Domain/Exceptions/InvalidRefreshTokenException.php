<?php

namespace Src\Auth\Domain\Exceptions;

use RuntimeException;

/**
 * Excepción de refresh token inválido o expirado.
 */
class InvalidRefreshTokenException extends RuntimeException
{
}

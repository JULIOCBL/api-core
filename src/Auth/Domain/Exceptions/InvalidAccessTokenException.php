<?php

namespace Src\Auth\Domain\Exceptions;

use RuntimeException;

/**
 * Excepción de access token inválido o expirado.
 */
class InvalidAccessTokenException extends RuntimeException
{
}

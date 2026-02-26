<?php

namespace Src\Auth\Domain\Exceptions;

use RuntimeException;

/**
 * Excepción cuando el usuario está bloqueado.
 */
class UserLockedException extends RuntimeException
{
}

<?php

namespace Src\Auth\Domain\Exceptions;

use RuntimeException;

/**
 * Excepción para intentos de login excedidos con intentos restantes.
 */
class LoginAttemptsExceededException extends RuntimeException
{
    /**
     * @param int $remaining_attempts
     */
    public function __construct(private int $remaining_attempts)
    {
        parent::__construct('Login attempts exceeded.');
    }

    /**
     * @return int
     */
    public function getRemainingAttempts(): int
    {
        return $this->remaining_attempts;
    }
}

<?php

namespace Src\Auth\Application\DataTransferObjects;

/**
 * DTO de entrada para cierre de sesión.
 */
class LogoutInput
{
    /**
     * @param string $access_token
     */
    public function __construct(private string $access_token)
    {
    }

    /**
     * @param string $access_token
     * @return self
     */
    public static function fromToken(string $access_token): self
    {
        return new self($access_token);
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }
}

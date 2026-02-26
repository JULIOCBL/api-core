<?php

namespace Src\Auth\Application\DataTransferObjects;

/**
 * DTO de entrada para refresh token.
 */
class RefreshTokenInput
{
    /**
     * @param string $refresh_token
     */
    public function __construct(private string $refresh_token)
    {
    }

    /**
     * @param array<string, mixed> $validated_data
     * @return self
     */
    public static function fromArray(array $validated_data): self
    {
        return new self((string) $validated_data['refresh_token']);
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }
}

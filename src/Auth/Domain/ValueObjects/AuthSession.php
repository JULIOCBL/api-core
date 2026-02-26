<?php

namespace Src\Auth\Domain\ValueObjects;

use Src\Auth\Domain\Entities\AuthenticatedUser;

/**
 * Value object con sesión autenticada y tokens emitidos.
 */
class AuthSession
{
    /**
     * @param string $access_token
     * @param string $refresh_token
     * @param string $token_type
     * @param int $expires_in
     * @param int $refresh_expires_in
     * @param AuthenticatedUser $user
     * @param array<int, string> $permissions
     */
    public function __construct(
        private string $access_token,
        private string $refresh_token,
        private string $token_type,
        private int $expires_in,
        private int $refresh_expires_in,
        private AuthenticatedUser $user,
        private array $permissions = []
    ) {
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->token_type;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expires_in;
    }

    /**
     * @return int
     */
    public function getRefreshExpiresIn(): int
    {
        return $this->refresh_expires_in;
    }

    /**
     * @return AuthenticatedUser
     */
    public function getUser(): AuthenticatedUser
    {
        return $this->user;
    }

    /**
     * @return array<int, string>
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array<int, string> $permissions
     * @return self
     */
    public function withPermissions(array $permissions): self
    {
        return new self(
            access_token: $this->access_token,
            refresh_token: $this->refresh_token,
            token_type: $this->token_type,
            expires_in: $this->expires_in,
            refresh_expires_in: $this->refresh_expires_in,
            user: $this->user,
            permissions: $permissions
        );
    }
}

<?php

namespace Src\Auth\Application\DataTransferObjects;

use Src\Auth\Domain\ValueObjects\ClientContext;

/**
 * DTO de entrada para login.
 */
class LoginInput
{
    /**
     * @param string $identifier
     * @param string $password
     * @param ClientContext $client_context
     */
    public function __construct(
        private string $identifier,
        private string $password,
        private ClientContext $client_context
    ) {
    }

    /**
     * @param array<string, mixed> $validated_data
     * @param string $ip
     * @param int $platform_type
     * @param string $name_platform_type
     * @param string $device_type
     * @param float $latitude
     * @param float $longitude
     * @param int|null $token_ttl_hours
     * @return self
     */
    public static function fromArray(
        array $validated_data,
        string $ip,
        int $platform_type,
        string $name_platform_type,
        string $device_type,
        float $latitude,
        float $longitude,
        ?int $token_ttl_hours = null
    ): self
    {
        $client_context = new ClientContext(
            ip: $ip,
            platform_type: $platform_type,
            name_platform_type: $name_platform_type,
            device_type: $device_type,
            latitude: $latitude,
            longitude: $longitude,
            token_ttl_hours: $token_ttl_hours
        );

        return new self(
            identifier: (string) $validated_data['identifier'],
            password: (string) $validated_data['password'],
            client_context: $client_context
        );
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return ClientContext
     */
    public function getClientContext(): ClientContext
    {
        return $this->client_context;
    }
}

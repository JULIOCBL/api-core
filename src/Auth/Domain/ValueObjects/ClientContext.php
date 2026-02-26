<?php

namespace Src\Auth\Domain\ValueObjects;

/**
 * Contexto de cliente para creación/rotación de tokens.
 */
class ClientContext
{
    /**
     * @param string $ip
     * @param int $platform_type
     * @param string $name_platform_type
     * @param string $device_type
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int|null $token_ttl_hours
     */
    public function __construct(
        private string $ip,
        private int $platform_type,
        private string $name_platform_type,
        private string $device_type,
        private ?float $latitude = null,
        private ?float $longitude = null,
        private ?int $token_ttl_hours = null
    ) {
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getPlatformType(): int
    {
        return $this->platform_type;
    }

    /**
     * @return string
     */
    public function getNamePlatformType(): string
    {
        return $this->name_platform_type;
    }

    /**
     * @return string
     */
    public function getDeviceType(): string
    {
        return $this->device_type;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @return int|null
     */
    public function getTokenTtlHours(): ?int
    {
        return $this->token_ttl_hours;
    }
}

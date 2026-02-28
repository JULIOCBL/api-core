<?php

namespace Src\Core\Infrastructure\Support\Helpers;

/**
 * Helper para trabajar con tipos de plataforma.
 */
final class PlatformTypeHelper
{
    public const WEB = 1;
    public const MOBILE = 2;
    public const DESKTOP = 3;
    public const INTEGRATION = 4;

    /**
     * @param int $platform_type
     * @return bool
     */
    public static function isValid(int $platform_type): bool
    {
        return in_array($platform_type, self::getAllowedValues(), true);
    }

    /**
     * @return array<int, int>
     */
    public static function getAllowedValues(): array
    {
        return [
            self::WEB,
            self::MOBILE,
            self::DESKTOP,
            self::INTEGRATION,
        ];
    }

    /**
     * @param int $platform_type
     * @return string
     */
    public static function toName(int $platform_type): string
    {
        return match ($platform_type) {
            self::WEB => 'web',
            self::MOBILE => 'mobile',
            self::DESKTOP => 'desktop',
            self::INTEGRATION => 'integration',
            default => 'unknown',
        };
    }

    /**
     * @return int|null
     */
    public static function getCurrent(): ?int
    {
        $platform_type = request()->attributes->get('auth_platform_type');

        if (is_numeric($platform_type)) {
            return (int) $platform_type;
        }

        $platform_type_header = request()->header('platform-type');
        if (is_numeric($platform_type_header)) {
            return (int) $platform_type_header;
        }

        return null;
    }

    /**
     * @return bool
     */
    public static function isWeb(): bool
    {
        return self::getCurrent() === self::WEB;
    }

    /**
     * @return bool
     */
    public static function isMobile(): bool
    {
        return self::getCurrent() === self::MOBILE;
    }

    /**
     * @return bool
     */
    public static function isDesktop(): bool
    {
        return self::getCurrent() === self::DESKTOP;
    }

    /**
     * @return bool
     */
    public static function isIntegration(): bool
    {
        return self::getCurrent() === self::INTEGRATION;
    }
}

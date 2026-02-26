<?php


namespace Src\Core\Infrastructure\Support\Utils;

use Carbon\Carbon;
use DateTimeZone;

/**
 * Extensión de Carbon que prioriza la zona horaria enviada por cliente.
 */
class ClientCarbon extends Carbon
{
    /**
     * Obtiene la fecha actual en la zona horaria del header `X-Timezone`
     * o en la zona configurada por defecto.
     *
     * @param DateTimeZone|string|int|null $timezone
     * @return static
     */
    public static function now(DateTimeZone|string|int|null $timezone = null): static
    {
        // Extraer desde header si no se pasa explícito
        $timezone ??= request()->header('X-Timezone');

        // Si viene 'null' como string, vacía o es inválida, usar config
        if (empty($timezone) || !in_array($timezone, timezone_identifiers_list())) {
            $timezone = config('app.timezone');
        }

        return parent::now($timezone);
    }
}

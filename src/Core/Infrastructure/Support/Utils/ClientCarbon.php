<?php

namespace Src\Core\Infrastructure\Support\Utils;

use Carbon\Carbon;
use DateTimeZone;

class ClientCarbon extends Carbon
{
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

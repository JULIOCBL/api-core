<?php

namespace Src\Core\Infrastructure\Http\Controllers;

use Src\Core\Infrastructure\Traits\ApiResponse;

/**
 * Controlador base del proyecto para respuestas estándar de API.
 */
abstract class Controller
{
    use ApiResponse;
}

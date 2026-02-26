<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Normaliza el código HTTP cuando llega en cero.
 *
 * @param int $code
 * @return int
 */
function errorCodeException(int $code): int
{
    $code = ($code == 0 ? 500 : $code);

    return $code;
}

/**
 * Reporta una excepción personalizada al canal de logs.
 *
 * @param string $type
 * @param Request $exception
 * @return void
 */
function reportCustom(string $type, Request $exception): void
{
    $json_exception = json_encode($exception->all());

    switch ($type) {
        case 'emergency':
            Log::emergency($json_exception);
            break;
        case 'alert':
            Log::alert($json_exception);
            break;
        case 'critical':
            Log::critical($json_exception);
            break;
        case 'error':
            Log::error($json_exception);
            break;
        case 'warning':
            Log::warning($json_exception);
            break;
        case 'notice':
            Log::notice($json_exception);
            break;
        case 'info':
            Log::info($json_exception);
            break;
        case 'debug':
            Log::debug($json_exception);
            break;
        default:
            Log::error((string) $json_exception);
            break;
    }
}

/**
 * Construye un objeto request con estructura uniforme de error.
 *
 * @param string $type
 * @param Request $request
 * @return Request
 */
function customException(string $type, Request $request): Request
{
    $array = [];

    if ($request->has('ip') && $request->ip == true) {
        $array['ip'] = request()->ip();
    }

    if ($request->has('title') && !empty($request->title)) {
        $array['title'] = Str::ascii($request->title);
    }
    if ($request->has('detail') && !empty($request->detail)) {
        $array['detail'] = Str::ascii($request->detail);
    }

    if ($request->has('status') && !empty($request->status)) {
        $array['status'] = $request->status;
    }

    if ($request->has('source') && !empty($request->source)) {
        $array['source'] = $request->source;
    }

    $exception = new Request($array);

    if ($request->has('report') && $request->report == true) {
        reportCustom($type, $exception);
    }

    return $exception;
}


/**
 * Obtiene información de error devuelta por un procedimiento almacenado.
 *
 * @param array<int, mixed> $error_info
 * @return \stdClass|null
 */
function storedProcedureErrorInfo(array $error_info): ?\stdClass
{

    if (isset($error_info[2])) {
        $json = $error_info[2];
        $decoded = json_decode($json, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return (object) $decoded;
        }
    }

    return null;
}

/**
 * Convierte un query string en arreglo asociativo.
 *
 * @param string $url
 * @return array<string, string>
 */
function linkToArray(string $url): array
{
    $pairs = explode('&', $url);
    $result = [];

    foreach ($pairs as $pair) {
        $parts = explode('=', $pair, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = $parts[0];
        $value = $parts[1];
        $result[urldecode($key)] = urldecode($value);
    }

    return $result;
}

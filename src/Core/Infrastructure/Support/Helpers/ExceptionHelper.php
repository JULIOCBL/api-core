<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

function errorCodeException($code)
{
    $code = ($code == 0 ? 500 : $code);

    return $code;
}

function reportCustom($type, Request $exception)
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
    }
}

function customException($type, Request $request)
{

    $array = array();

    if ($request->has('ip') && $request->ip == true) {
        $array['ip'] = request()->ip();
    }

    if ($request->has('title') && !empty($request->title)) {
        $array['title'] = Str::ascii($request->title);
    }
    if ($request->has(' ') && !empty($request->detail)) {
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


function storedProcedureErrorInfo($error_info)
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

function linkToArray($url)
{
    // Divide la cadena en pares clave-valor
    $pairs = explode('&', $url);

    // Inicializa el arreglo de resultados
    $result = [];

    // Itera sobre cada par clave-valor
    foreach ($pairs as $pair) {
        // Divide el par clave-valor en clave y valor
        list($key, $value) = explode('=', $pair);
        // Decodifica el valor y añádelo al arreglo resultante
        $result[urldecode($key)] = urldecode($value);
    }

    return $result;
}

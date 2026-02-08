<?php

namespace Src\Core\Infrastructure\Traits;


use Illuminate\Http\Response;

trait ApiResponse
{
    public function successResponse($data, $message = '', $code = Response::HTTP_OK)
    {
        $body['data'] = $data;

        if (!empty($message)) {
            $body['message'] = $message;
        }

        return response($body, $code)->header('Content-Type', 'application/json');
    }


    public function errorsMessage(array $message, int $code, int $error_code = 1000)
    {
        $code = errorCodeException($code);

        if (!isset($message["code"])) {
            $message["code"] = $error_code;
        }

        return response(['errors' => $message], $code)->header('Content-Type', 'application/json');
    }
}

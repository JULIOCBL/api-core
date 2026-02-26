<?php


namespace Src\Core\Infrastructure\Traits;


use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Trait para estandarizar respuestas exitosas y de error en API.
 */
trait ApiResponse
{
    /**
     * @param mixed $data
     * @param array<string, string>|string $message
     * @param int $code
     * @return Response
     */
    public function successResponse(mixed $data, array|string $message = '', int $code = HttpResponse::HTTP_OK): Response
    {
        $body = [];
        $body['data'] = $data;

        if (!empty($message)) {
            $message_data = is_array($message)
                ? (object) $message
                : (object) ['description' => $message];

            $body['message'] = [
                'title' => $message_data->title ?? '',
                'description' => $message_data->description ?? ''
            ];
        }

        return response($body, $code)->header('Content-Type', 'application/json');
    }


    /**
     * @param array<string, mixed> $message
     * @param int $code
     * @param int $error_code
     * @return Response
     */
    public function errorsMessage(array $message, int $code, int $error_code = 1000): Response
    {
        $code = errorCodeException($code);

        if (!isset($message['code'])) {
            $message['code'] = $error_code;
        }

        return response(['errors' => $message], $code)->header('Content-Type', 'application/json');
    }
}

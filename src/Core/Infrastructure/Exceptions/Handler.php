<?php

namespace Src\Core\Infrastructure\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Src\Core\Infrastructure\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{

    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {

        if ($exception instanceof JsonException) {

            return $this->errorsMessage(
                [
                    "status" => errorCodeException($exception->getCode()),
                    "title" => $exception->getTitle(),
                    "message" => $exception->getMessage(),
                ],
                $exception->getCode(),
                $exception->getErrorCode()
            );
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorsMessage(
                [
                    "status" => errorCodeException($exception->getCode()),
                    "title" =>  __("auth.user_access.title"),
                    "message" => __("auth.user_access.description")
                ],
                $exception->getCode(),
                1000,
            );
        }


        if ($exception instanceof QueryException) {


            if ($exception->getCode() == 45000) {

                if (isset($exception->errorInfo)) {

                    $error_info = storedProcedureErrorInfo($exception->errorInfo);

                    if ($error_info != null) {
                        $replace = [];

                        if (isset($error_info->data_params)) {
                            $replace = linkToArray($error_info->data_params);
                        }

                        return $this->errorsMessage(
                            [
                                "status" => $error_info->codigo,
                                "title" =>  __($error_info->title),
                                "message" => __($error_info->description, $replace)
                            ],
                            $error_info->codigo,
                            1000,
                        );
                    }
                }
            }
        }

      /*   if (env('APP_DEBUG', false)) {
            return $this->errorsMessage(
                [
                    "status" =>  Response::HTTP_INTERNAL_SERVER_ERROR,
                    "title" => 'Error inesperado',
                    "message" => 'Error inesperado'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                1000,
            );
        } */


        return parent::render($request, $exception);
    }
}

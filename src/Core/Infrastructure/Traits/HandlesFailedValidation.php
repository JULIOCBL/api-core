<?php


namespace Src\Core\Infrastructure\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Trait para estandarizar el formato de error de validación en requests.
 */
trait HandlesFailedValidation
{
    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();
        $first_message = $errors->first();

        throw new HttpResponseException(response()->json([
            'errors' => [
                'status' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'title' => __('shared::request.field_review_required'),
                'message' => $first_message,
                'details' => $errors,
                'code' => ErrorCodes::GENERAL_BAD_REQUEST_9001,
            ]
        ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}

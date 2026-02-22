<?php


namespace Src\Core\Infrastructure\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait HandlesFailedValidation
{
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
                'code' => 1000,
            ]
        ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}

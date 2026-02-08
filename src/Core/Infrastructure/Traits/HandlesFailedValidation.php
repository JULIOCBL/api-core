<?php

namespace Src\Core\Infrastructure\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

trait HandlesFailedValidation
{
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $first_message = $errors->first();

        throw new HttpResponseException(response()->json([
            'errors' => [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'title' => __('shared::request.field_review_required'),
                'message' => $first_message,
                'details' => $errors,
                'code' => 1000,
            ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

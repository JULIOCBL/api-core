<?php

namespace Src\Auth\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;

/**
 * Request de validación para logout.
 */
class LogoutRequest extends FormRequest
{
    use HandlesFailedValidation;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'access_token' => ['sometimes', 'string'],
        ];
    }
}

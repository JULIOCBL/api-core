<?php

namespace Src\Auth\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;

/**
 * Request de validación para refresh token.
 */
class RefreshTokenRequest extends FormRequest
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
            'refresh_token' => ['required', 'string'],
        ];
    }
}

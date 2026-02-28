<?php

namespace Src\Auth\Infrastructure\Http\Requests;

use Psr\Log\LogLevel;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Request de validación para login.
 */
class LoginRequest extends FormRequest
{
    use HandlesFailedValidation;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        if (!$this->hasHeader('platform-type')) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.missing_header_platform_type.title'),
                __('auth::login.missing_header_platform_type.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_MISSING_PLATFORM_HEADER_1007
            );
        }

        $platform_type = (string) $this->header('platform-type');
        if (!in_array($platform_type, ['1', '2', '3', '4'], true)) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.invalid_header_platform_type.title'),
                __('auth::login.invalid_header_platform_type.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_INVALID_PLATFORM_HEADER_1008
            );
        }

        if ($this->hasHeader('token-ttl-hours')) {
            if ($platform_type !== '4') {
                throw new JsonException(
                    LogLevel::WARNING,
                    __('auth::login.invalid_header_token_ttl_hours.title'),
                    __('auth::login.invalid_header_token_ttl_hours.description'),
                    HttpResponse::HTTP_BAD_REQUEST,
                    '',
                    ErrorCodes::AUTH_INVALID_TOKEN_TTL_HOURS_1016
                );
            }

            $token_ttl_hours = (string) $this->header('token-ttl-hours');
            if (!ctype_digit($token_ttl_hours) || (int) $token_ttl_hours < 8 || (int) $token_ttl_hours > 24) {
                throw new JsonException(
                    LogLevel::WARNING,
                    __('auth::login.invalid_header_token_ttl_hours.title'),
                    __('auth::login.invalid_header_token_ttl_hours.description'),
                    HttpResponse::HTTP_BAD_REQUEST,
                    '',
                    ErrorCodes::AUTH_INVALID_TOKEN_TTL_HOURS_1016
                );
            }
        }

        if (!$this->hasHeader('X-Latitude')) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.missing_header_latitude.title'),
                __('auth::login.missing_header_latitude.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_MISSING_LATITUDE_HEADER_1017
            );
        }

        if (!$this->hasHeader('X-Longitude')) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.missing_header_longitude.title'),
                __('auth::login.missing_header_longitude.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_MISSING_LONGITUDE_HEADER_1018
            );
        }

        $latitude_header = (string) $this->header('X-Latitude');
        if (!is_numeric($latitude_header) || (float) $latitude_header < -90 || (float) $latitude_header > 90) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.invalid_header_latitude.title'),
                __('auth::login.invalid_header_latitude.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_INVALID_LATITUDE_HEADER_1019
            );
        }

        $longitude_header = (string) $this->header('X-Longitude');
        if (!is_numeric($longitude_header) || (float) $longitude_header < -180 || (float) $longitude_header > 180) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.invalid_header_longitude.title'),
                __('auth::login.invalid_header_longitude.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_INVALID_LONGITUDE_HEADER_1020
            );
        }

        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'identifier.required' => __('auth::login.invalid_credentials.description'),
            'password.required' => __('auth::login.invalid_credentials.description'),
        ];
    }
}

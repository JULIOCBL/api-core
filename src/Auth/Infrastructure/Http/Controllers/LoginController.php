<?php

namespace Src\Auth\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Psr\Log\LogLevel;
use Src\Auth\Application\DataTransferObjects\LoginInput;
use Src\Auth\Application\UseCases\LoginUseCase;
use Src\Auth\Domain\Exceptions\InvalidCredentialsException;
use Src\Auth\Domain\Exceptions\UserLockedException;
use Src\Auth\Infrastructure\Http\Presenters\AuthResponsePresenter;
use Src\Auth\Infrastructure\Http\Requests\LoginRequest;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Controlador para inicio de sesión.
 */
class LoginController extends Controller
{
    /**
     * @param LoginUseCase $login_use_case
     * @param AuthResponsePresenter $auth_response_presenter
     */
    public function __construct(
        private LoginUseCase $login_use_case,
        private AuthResponsePresenter $auth_response_presenter
    ) {
    }

    /**
     * @param LoginRequest $login_request
     * @return Response
     */
    public function login(LoginRequest $login_request): Response
    {
        $validated_data = $login_request->validated();
        $platform_type = (int) $login_request->header('platform-type');
        $token_ttl_hours_header = $login_request->header('token-ttl-hours');
        $token_ttl_hours = $token_ttl_hours_header !== null && $token_ttl_hours_header !== ''
            ? (int) $token_ttl_hours_header
            : null;
        $latitude = (float) $login_request->header('X-Latitude');
        $longitude = (float) $login_request->header('X-Longitude');
        $name_platform_type = $this->resolvePlatformName($platform_type);
        $device_type = (string) $platform_type;

        $login_input = LoginInput::fromArray(
            $validated_data,
            (string) $login_request->ip(),
            $platform_type,
            $name_platform_type,
            $device_type,
            $latitude,
            $longitude,
            $token_ttl_hours
        );

        try {
            $auth_session = $this->login_use_case->execute($login_input);
        } catch (UserLockedException $user_locked_exception) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.temporarily_locked.title'),
                __('auth::login.temporarily_locked.description'),
                HttpResponse::HTTP_LOCKED,
                '',
                ErrorCodes::AUTH_USER_LOCKED_TEMPORARILY
            );
        } catch (InvalidCredentialsException $invalid_credentials_exception) {
            $attempts = (int) $invalid_credentials_exception->getMessage();

            if ($attempts > 0) {
                throw new JsonException(
                    LogLevel::WARNING,
                    __('auth::login.last_attempt_before_lockout.title'),
                    __('auth::login.last_attempt_before_lockout.description', ['attempt' => $attempts]),
                    HttpResponse::HTTP_TOO_MANY_REQUESTS,
                    '',
                    ErrorCodes::AUTH_LOGIN_ATTEMPTS_EXCEEDED
                );
            }

            throw new JsonException(
                LogLevel::WARNING,
                __('auth::login.invalid_credentials.title'),
                __('auth::login.invalid_credentials.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_INVALID_CREDENTIALS
            );
        }

        $response_data = $this->auth_response_presenter->presentSession($auth_session);

        return $this->successResponse(
            $response_data,
            [
                'title' => __('auth::session.login_success_title'),
                'description' => __('auth::session.login_success_description'),
            ],
            HttpResponse::HTTP_OK
        );
    }

    /**
     * @param int $platform_type
     * @return string
     */
    private function resolvePlatformName(int $platform_type): string
    {
        return match ($platform_type) {
            1 => 'web',
            2 => 'mobile',
            3 => 'desktop',
            4 => 'integration',
            default => 'unknown',
        };
    }
}

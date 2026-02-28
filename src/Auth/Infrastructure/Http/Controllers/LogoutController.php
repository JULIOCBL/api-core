<?php

namespace Src\Auth\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Psr\Log\LogLevel;
use Src\Auth\Application\DataTransferObjects\LogoutInput;
use Src\Auth\Application\UseCases\LogoutUseCase;
use Src\Auth\Domain\Exceptions\InvalidAccessTokenException;
use Src\Auth\Infrastructure\Http\Requests\LogoutRequest;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Controlador para cierre de sesión.
 */
class LogoutController extends Controller
{
    /**
     * @param LogoutUseCase $logout_use_case
     */
    public function __construct(private LogoutUseCase $logout_use_case)
    {
    }

    /**
     * @param LogoutRequest $logout_request
     * @return Response
     */
    public function logout(LogoutRequest $logout_request): Response
    {
        $validated_data = $logout_request->validated();
        $access_token = $logout_request->bearerToken();

        if (($access_token === null || $access_token === '') && isset($validated_data['access_token'])) {
            $access_token = (string) $validated_data['access_token'];
        }

        if ($access_token === null || $access_token === '') {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.missing_access_token.title'),
                __('auth::session.missing_access_token.description'),
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                '',
                ErrorCodes::AUTH_MISSING_ACCESS_TOKEN_1009
            );
        }

        $logout_input = LogoutInput::fromToken($access_token);
        try {
            $this->logout_use_case->execute($logout_input);
        } catch (InvalidAccessTokenException $invalid_access_token_exception) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_INVALID_ACCESS_TOKEN_1010
            );
        }

        return $this->successResponse(
            [],
            [
                'title' => __('auth::session.logout_success.title'),
                'description' => __('auth::session.logout_success.description'),
            ],
            HttpResponse::HTTP_OK
        );
    }
}

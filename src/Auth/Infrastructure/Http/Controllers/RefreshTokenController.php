<?php

namespace Src\Auth\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Src\Auth\Application\DataTransferObjects\RefreshTokenInput;
use Src\Auth\Application\UseCases\RefreshTokenUseCase;
use Src\Auth\Domain\Exceptions\InvalidRefreshTokenException;
use Src\Auth\Infrastructure\Http\Presenters\AuthResponsePresenter;
use Src\Auth\Infrastructure\Http\Requests\RefreshTokenRequest;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Controlador para refrescar tokens de sesión.
 */
class RefreshTokenController extends Controller
{
    /**
     * @param RefreshTokenUseCase $refresh_token_use_case
     * @param AuthResponsePresenter $auth_response_presenter
     */
    public function __construct(
        private RefreshTokenUseCase $refresh_token_use_case,
        private AuthResponsePresenter $auth_response_presenter
    ) {
    }

    /**
     * @param RefreshTokenRequest $refresh_token_request
     * @return Response
     */
    public function refresh(RefreshTokenRequest $refresh_token_request): Response
    {
        $refresh_token_input = RefreshTokenInput::fromArray($refresh_token_request->validated());
        try {
            $auth_session = $this->refresh_token_use_case->execute($refresh_token_input);
        } catch (InvalidRefreshTokenException $invalid_refresh_token_exception) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_refresh_token.title'),
                __('auth::session.invalid_refresh_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $response_data = $this->auth_response_presenter->presentSession($auth_session);

        return $this->successResponse(
            $response_data,
            [
                'title' => __('auth::session.refresh_success.title'),
                'description' => __('auth::session.refresh_success.description'),
            ],
            HttpResponse::HTTP_OK
        );
    }
}

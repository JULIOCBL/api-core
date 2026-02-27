<?php

namespace Src\Auth\Infrastructure\Http\Middleware;

use Closure;
use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Log\LogLevel;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Support\Utils\ClientCarbon;
use Src\Core\Infrastructure\Support\Utils\Token;
use Src\Core\Infrastructure\Support\Utils\TokenHash;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\PersonalAccessToken;
use stdClass;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use TypeError;
use UnexpectedValueException;

/**
 * Middleware para validar access token JWT y adjuntar contexto al request.
 */
class VerifyAccessTokenMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $access_token = $request->bearerToken();
        if ($access_token === null || $access_token === '') {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.missing_bearer_token.title'),
                __('auth::session.missing_bearer_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $payload = $this->decodeAccessToken($access_token);
        $jwtid = isset($payload->jwtid) ? (int) $payload->jwtid : 0;
        $platform_type = isset($payload->{'platform-type'}) ? (string) $payload->{'platform-type'} : '';
        $access_token_hash = TokenHash::make($access_token);

        if ($jwtid <= 0 || $platform_type === '') {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $personal_access_token = PersonalAccessToken::query()
            ->where('id', $jwtid)
            ->where('token', $access_token_hash)
            ->whereNull('deleted_at')
            ->where('expires_at', '>', ClientCarbon::now())
            ->first();

        if ($personal_access_token === null) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        if ((string) $personal_access_token->platform_type !== $platform_type) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $personal_access_token->last_used_at = ClientCarbon::now();
        $personal_access_token->save();

        $request->attributes->set('auth_platform_type', (int) $personal_access_token->platform_type);
        $request->attributes->set('auth_permissions', $this->decodeAbilities($personal_access_token->abilities));

        return $next($request);
    }

    /**
     * @param string $access_token
     * @return stdClass
     */
    private function decodeAccessToken(string $access_token): stdClass
    {
        try {
            $payload = Token::decode($access_token);
        } catch (
            DecryptException
            | DomainException
            | BeforeValidException
            | ExpiredException
            | SignatureInvalidException
            | TypeError
            | UnexpectedValueException $exception
        ) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $token_not_before = isset($payload->nbf) ? (int) $payload->nbf : 0;
        $token_expiration = isset($payload->exp) ? (int) $payload->exp : 0;
        if ($token_not_before <= 0 || $token_expiration <= ClientCarbon::now()->timestamp) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        return $payload;
    }

    /**
     * @param mixed $abilities
     * @return array<int, string>
     */
    private function decodeAbilities(mixed $abilities): array
    {
        if ($abilities === null || $abilities === '') {
            return [];
        }

        if (is_array($abilities)) {
            return $abilities;
        }

        if (is_string($abilities)) {
            $decoded_abilities = json_decode($abilities, true);
            if (is_array($decoded_abilities)) {
                return $decoded_abilities;
            }
        }

        throw new JsonException(
            LogLevel::WARNING,
            __('auth::session.invalid_access_token.title'),
            __('auth::session.invalid_access_token.description'),
            Response::HTTP_FORBIDDEN,
            '',
            ErrorCodes::AUTH_UNAUTHORIZED_1003
        );
    }
}

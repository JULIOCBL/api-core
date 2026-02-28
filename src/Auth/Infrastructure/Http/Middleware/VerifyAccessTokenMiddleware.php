<?php

namespace Src\Auth\Infrastructure\Http\Middleware;

use Closure;
use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Psr\Log\LogLevel;
use Src\Auth\Infrastructure\Contracts\AuthUserQueryBuilderInterface;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Support\Utils\ClientCarbon;
use Src\Core\Infrastructure\Support\Utils\Token;
use Src\Core\Infrastructure\Support\Utils\TokenHash;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\PersonalAccessToken;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;
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
     * @param AuthUserQueryBuilderInterface $auth_user_query_builder
     */
    public function __construct(private AuthUserQueryBuilderInterface $auth_user_query_builder)
    {
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $access_token = $this->getAccessTokenFromRequest($request);
        $payload = $this->decodeAccessToken($access_token);
        $jwtid = $this->extractJwtId($payload);
        $platform_type = $this->extractPlatformType($payload);
        $personal_access_token = $this->resolvePersonalAccessToken($jwtid, $access_token);

        $this->assertPlatformTypeMatches($personal_access_token, $platform_type);
        $this->touchLastUsedAt($personal_access_token);

        $auth_user = $this->resolveAuthUser((string) $personal_access_token->user_id);

        auth()->guard()->setUser($auth_user);

        $this->applyRequestAuthContext($request, $personal_access_token);

        return $next($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getAccessTokenFromRequest(Request $request): string
    {
        $access_token = $request->bearerToken();
        if ($access_token === null || $access_token === '') {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.missing_bearer_token.title'),
                __('auth::session.missing_bearer_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_MISSING_BEARER_TOKEN_1012
            );
        }

        return $access_token;
    }

    /**
     * @param stdClass $payload
     * @return int
     */
    private function extractJwtId(stdClass $payload): int
    {
        $jwtid = isset($payload->jwtid) ? (int) $payload->jwtid : 0;
        if ($jwtid <= 0) {
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_PAYLOAD_INVALID_1022);
        }

        return $jwtid;
    }

    /**
     * @param stdClass $payload
     * @return string
     */
    private function extractPlatformType(stdClass $payload): string
    {
        $platform_type = isset($payload->{'platform-type'}) ? (string) $payload->{'platform-type'} : '';
        if ($platform_type === '') {
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_PAYLOAD_INVALID_1022);
        }

        return $platform_type;
    }

    /**
     * @param int $jwtid
     * @param string $access_token
     * @return PersonalAccessToken
     */
    private function resolvePersonalAccessToken(int $jwtid, string $access_token): PersonalAccessToken
    {
        $access_token_hash = TokenHash::make($access_token);

        $personal_access_token = PersonalAccessToken::query()
            ->where('id', $jwtid)
            ->where('token', $access_token_hash)
            ->whereNull('deleted_at')
            ->where('expires_at', '>', ClientCarbon::now())
            ->first();

        if ($personal_access_token === null) {
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_RECORD_NOT_FOUND_1023);
        }

        return $personal_access_token;
    }

    /**
     * @param PersonalAccessToken $personal_access_token
     * @param string $platform_type
     * @return void
     */
    private function assertPlatformTypeMatches(PersonalAccessToken $personal_access_token, string $platform_type): void
    {
        if ((string) $personal_access_token->platform_type !== $platform_type) {
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_PLATFORM_MISMATCH_1024);
        }
    }

    /**
     * @param PersonalAccessToken $personal_access_token
     * @return void
     */
    private function touchLastUsedAt(PersonalAccessToken $personal_access_token): void
    {
        $personal_access_token->last_used_at = ClientCarbon::now();
        $personal_access_token->save();
    }

    /**
     * @param string $user_id
     * @return User
     */
    private function resolveAuthUser(string $user_id): User
    {
        $auth_user = $this->auth_user_query_builder->findById($user_id);

        if ($auth_user === null || (int) $auth_user->user_status_id !== 1) {
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_USER_INVALID_1027);
        }

        return $auth_user;
    }

    /**
     * @param Request $request
     * @param PersonalAccessToken $personal_access_token
     * @return void
     */
    private function applyRequestAuthContext(Request $request, PersonalAccessToken $personal_access_token): void
    {
        $request->attributes->set('auth_platform_type', (int) $personal_access_token->platform_type);
        $request->attributes->set('auth_permissions', $this->decodeAbilities($personal_access_token->abilities));
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
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_DECODE_ERROR_1021);
        }

        $token_not_before = isset($payload->nbf) ? (int) $payload->nbf : 0;
        $token_expiration = isset($payload->exp) ? (int) $payload->exp : 0;
        if ($token_not_before <= 0 || $token_expiration <= ClientCarbon::now()->timestamp) {
            throw $this->invalidAccessTokenException(HttpResponse::HTTP_UNAUTHORIZED, ErrorCodes::AUTH_ACCESS_TOKEN_EXPIRED_1025);
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

        throw $this->invalidAccessTokenException(HttpResponse::HTTP_FORBIDDEN, ErrorCodes::AUTH_ACCESS_TOKEN_ABILITIES_INVALID_1026);
    }

    /**
     * @param int $status_code
     * @param int $error_code
     * @return JsonException
     */
    private function invalidAccessTokenException(int $status_code, int $error_code): JsonException
    {
        return new JsonException(
            LogLevel::WARNING,
            __('auth::session.invalid_access_token.title'),
            __('auth::session.invalid_access_token.description'),
            $status_code,
            '',
            $error_code
        );
    }
}

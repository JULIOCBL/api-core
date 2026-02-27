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
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Support\Utils\ClientCarbon;
use Src\Core\Infrastructure\Support\Utils\Token;
use Src\Core\Infrastructure\Support\Utils\TokenHash;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Company;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\PersonalAccessToken;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\UserType;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use TypeError;
use UnexpectedValueException;

/**
 * Middleware para resolver contexto de usuario autenticado y compañía activa.
 */
class ResolveAuthUserContextMiddleware
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
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $payload = $this->decodeAccessToken($access_token);
        $jwtid = isset($payload->jwtid) ? (int) $payload->jwtid : 0;
        $access_token_hash = TokenHash::make($access_token);
        if ($jwtid <= 0) {
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

        $auth_user = User::query()
            ->select([
                'users.id',
                'users.role_id',
                'users.user_status_id',
                'roles.user_type_id',
                'roles.company_id as role_company_id',
            ])
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', (string) $personal_access_token->user_id)
            ->whereNull('users.deleted_at')
            ->first();

        if ($auth_user === null || (int) $auth_user->user_status_id !== 1) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_access_token.title'),
                __('auth::session.invalid_access_token.description'),
                HttpResponse::HTTP_UNAUTHORIZED,
                '',
                ErrorCodes::AUTH_UNAUTHORIZED_1003
            );
        }

        $user_type_id = (int) $auth_user->user_type_id;
        $company_id = $this->resolveCompanyId($request, $user_type_id, $auth_user->role_company_id);

        $request->setUserResolver(fn() => $auth_user);
        $request->attributes->set('auth_level_user', $this->resolveLevelUser($user_type_id));
        $request->attributes->set('auth_company_id', $company_id);
        $request->attributes->set('auth_role_id', (string) $auth_user->role_id);

        return $next($request);
    }

    /**
     * @param string $access_token
     * @return mixed
     */
    private function decodeAccessToken(string $access_token): mixed
    {
        try {
            return Token::decode($access_token);
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
    }

    /**
     * @param Request $request
     * @param int $user_type_id
     * @param mixed $role_company_id
     * @return int|null
     */
    private function resolveCompanyId(Request $request, int $user_type_id, mixed $role_company_id): ?int
    {
        if ($user_type_id === UserType::ROOT_1) {
            $requested_company_id = $request->input('company_id', $request->query('company_id'));
            if ($requested_company_id === null || $requested_company_id === '') {
                return null;
            }

            if (!is_numeric($requested_company_id)) {
                throw new JsonException(
                    LogLevel::WARNING,
                    __('auth::session.invalid_company_context.title'),
                    __('auth::session.invalid_company_context.description'),
                    HttpResponse::HTTP_BAD_REQUEST,
                    '',
                    ErrorCodes::AUTH_LOGIN_ERROR_1001
                );
            }

            $company_id = (int) $requested_company_id;
            $company_exists = Company::query()->whereKey($company_id)->whereNull('deleted_at')->exists();
            if (!$company_exists) {
                throw new JsonException(
                    LogLevel::WARNING,
                    __('auth::session.invalid_company_context.title'),
                    __('auth::session.invalid_company_context.description'),
                    HttpResponse::HTTP_BAD_REQUEST,
                    '',
                    ErrorCodes::AUTH_LOGIN_ERROR_1001
                );
            }

            return $company_id;
        }

        if ($role_company_id === null) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.missing_company_context.title'),
                __('auth::session.missing_company_context.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_LOGIN_ERROR_1001
            );
        }

        return (int) $role_company_id;
    }

    /**
     * @param int $user_type_id
     * @return string
     */
    private function resolveLevelUser(int $user_type_id): string
    {
        return match ($user_type_id) {
            UserType::ROOT_1 => 'SUPERADMIN',
            UserType::SUPER_USUARIO_2 => 'ADMINISTRATOR',
            UserType::ADMINISTRATOR_3, UserType::USER_4 => 'USER',
            default => 'USER',
        };
    }
}

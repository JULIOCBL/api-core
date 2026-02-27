<?php

namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Src\Auth\Domain\Contracts\AuthGatewayInterface;
use Src\Auth\Domain\Entities\AuthenticatedUser;
use Src\Auth\Domain\Exceptions\InvalidAccessTokenException;
use Src\Auth\Domain\Exceptions\InvalidCredentialsException;
use Src\Auth\Domain\Exceptions\InvalidRefreshTokenException;
use Src\Auth\Domain\Exceptions\UserLockedException;
use Src\Auth\Domain\ValueObjects\AuthSession;
use Src\Auth\Domain\ValueObjects\ClientContext;
use Src\Core\Infrastructure\Support\Utils\ClientCarbon;
use Src\Core\Infrastructure\Support\Utils\Token;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\PersonalAccessToken;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\RefreshAccessToken;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;
use Throwable;
use stdClass;

/**
 * Adaptador Eloquent para autenticación y gestión de tokens.
 */
class EloquentAuthGateway implements AuthGatewayInterface
{
    private const TOKEN_TYPE = 'Bearer';
    private const ACCESS_TOKEN_TTL_MINUTES = 60;
    private const REFRESH_TOKEN_TTL_MINUTES = 43200;

    /**
     * @param string $identifier
     * @param string $password
     * @param ClientContext $client_context
     * @return AuthSession
     */
    public function login(string $identifier, string $password, ClientContext $client_context): AuthSession
    {
        $auth_exception = null;

        $auth_session = DB::transaction(function () use ($identifier, $password, $client_context, &$auth_exception): ?AuthSession {
            $user_model = $this->getUserByIdentifier($identifier);

            if ($user_model === null) {
                $auth_exception = new InvalidCredentialsException('Invalid credentials.');
                return null;
            }

            if (in_array((int) $user_model->user_status_id, [2, 3, 4], true)) {
                $auth_exception = new UserLockedException('The user is locked.');
                return null;
            }

            if (!$user_model->attempt($password)) {
                $remaining_attempts = (int) $user_model->session_attempts - 1;

                if ($remaining_attempts <= 0) {
                    $user_model->session_attempts = 0;
                    $user_model->user_status_id = 4;
                    $user_model->save();

                    $auth_exception = new UserLockedException('The user is temporarily locked.');
                    return null;
                }

                $user_model->session_attempts = $remaining_attempts;
                $user_model->save();

                $auth_exception = new InvalidCredentialsException((string) $remaining_attempts);
                return null;
            }

            $user_model->session_attempts = (int) $user_model->session_attempts_mirror;
            $user_model->save();

            $authenticated_user = $this->toAuthenticatedUser($user_model);

            return $this->createSessionForUser($authenticated_user, $client_context);
        });

        if ($auth_exception !== null) {
            throw $auth_exception;
        }

        if ($auth_session === null) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        return $auth_session;
    }

    /**
     * @param string $refresh_token
     * @return AuthSession
     */
    public function refresh(string $refresh_token): AuthSession
    {
        return DB::transaction(function () use ($refresh_token): AuthSession {
            $refresh_token_payload = $this->decodeTokenPayload($refresh_token, 'refresh');
            $refresh_token_id = (int) ($refresh_token_payload->jwtid ?? 0);
            if ($refresh_token_id <= 0) {
                throw new InvalidRefreshTokenException('Invalid refresh token.');
            }

            $refresh_model = RefreshAccessToken::query()
                ->where('id', $refresh_token_id)
                ->where('token', $refresh_token)
                ->where('expires_at', '>', ClientCarbon::now())
                ->first();

            if ($refresh_model === null) {
                throw new InvalidRefreshTokenException('Invalid refresh token.');
            }

            $personal_access_token_model = PersonalAccessToken::query()
                ->whereKey((int) $refresh_model->personal_access_token_id)
                ->whereNull('deleted_at')
                ->where('expires_at', '>', ClientCarbon::now())
                ->first();

            if ($personal_access_token_model === null) {
                throw new InvalidRefreshTokenException('Refresh token is not linked to an active session.');
            }

            $user_model = $this->getUserById((string) $personal_access_token_model->user_id);

            if ($user_model === null || (int) $user_model->user_status_id !== 1) {
                throw new InvalidRefreshTokenException('The user is not active.');
            }

            $client_context = new ClientContext(
                ip: (string) ($personal_access_token_model->ip_address ?? $personal_access_token_model->ip ?? ''),
                platform_type: (int) $personal_access_token_model->platform_type,
                name_platform_type: (string) $personal_access_token_model->name_platform_type,
                device_type: (string) $personal_access_token_model->device_type,
                latitude: $personal_access_token_model->latitude !== null ? (float) $personal_access_token_model->latitude : null,
                longitude: $personal_access_token_model->longitude !== null ? (float) $personal_access_token_model->longitude : null
            );

            $refresh_model->delete();
            $personal_access_token_model->delete();

            $authenticated_user = $this->toAuthenticatedUser($user_model);

            return $this->createSessionForUser($authenticated_user, $client_context);
        });
    }

    /**
     * @param string $access_token
     * @return void
     */
    public function logout(string $access_token): void
    {
        DB::transaction(function () use ($access_token): void {
            $this->decodeTokenPayload($access_token, 'access');

            $personal_access_token_model = PersonalAccessToken::query()
                ->where('token', $access_token)
                ->whereNull('deleted_at')
                ->first();

            if ($personal_access_token_model === null) {
                throw new InvalidAccessTokenException('Access token not found.');
            }

            RefreshAccessToken::query()
                ->where('personal_access_token_id', (int) $personal_access_token_model->id)
                ->delete();

            $personal_access_token_model->delete();
        });
    }

    /**
     * @param AuthenticatedUser $authenticated_user
     * @param ClientContext $client_context
     * @return AuthSession
     */
    private function createSessionForUser(AuthenticatedUser $authenticated_user, ClientContext $client_context): AuthSession
    {
        $now_client = ClientCarbon::now();
        $access_ttl_minutes = $this->getAccessTtlMinutes();
        if ($client_context->getPlatformType() === 4 && $client_context->getTokenTtlHours() !== null) {
            $access_ttl_minutes = $client_context->getTokenTtlHours() * 60;
        }

        $access_expiration_date = (clone $now_client)->addMinutes($access_ttl_minutes);
        $refresh_ttl_minutes = $this->getRefreshTtlMinutes();
        $refresh_expiration_date = (clone $now_client)->addMinutes($refresh_ttl_minutes);

        $personal_access_token_model = PersonalAccessToken::query()->create([
            'ip_address' => $client_context->getIp(),
            'latitude' => $client_context->getLatitude(),
            'longitude' => $client_context->getLongitude(),
            'tokenable_type' => User::class,
            'user_id' => $authenticated_user->getId(),
            'platform_type' => $client_context->getPlatformType(),
            'name_platform_type' => $client_context->getNamePlatformType(),
            'device_type' => $client_context->getDeviceType(),
            'token' => '',
            'abilities' => null,
            'expires_at' => $access_expiration_date,
            'created_at_utc' => $now_client,
            'updated_at_utc' => $now_client,
        ]);

        $access_payload = $this->buildTokenPayload(
            expiration_timestamp: $access_expiration_date->timestamp,
            jwtid: (string) $personal_access_token_model->id,
            platform_type: $client_context->getPlatformType(),
            ip: $client_context->getIp()
        );
        $access_token = Token::encode($access_payload);

        $personal_access_token_model->token = $access_token;
        $personal_access_token_model->save();

        $refresh_model = RefreshAccessToken::query()->create([
            'personal_access_token_id' => (int) $personal_access_token_model->id,
            'token' => '',
            'expires_at' => $refresh_expiration_date,
        ]);

        $refresh_payload = $this->buildTokenPayload(
            expiration_timestamp: $refresh_expiration_date->timestamp,
            jwtid: (string) $refresh_model->id,
            platform_type: $client_context->getPlatformType(),
            ip: $client_context->getIp()
        );
        $refresh_token = Token::encode($refresh_payload);

        $refresh_model->token = $refresh_token;
        $refresh_model->save();

        return new AuthSession(
            access_token: $access_token,
            refresh_token: $refresh_token,
            token_type: self::TOKEN_TYPE,
            expires_in: $access_ttl_minutes * 60,
            refresh_expires_in: $refresh_ttl_minutes * 60,
            user: $authenticated_user,
            permissions: []
        );
    }

    /**
     * @param User $user_model
     * @return AuthenticatedUser
     */
    private function toAuthenticatedUser(User $user_model): AuthenticatedUser
    {
        return new AuthenticatedUser(
            id: (string) $user_model->id,
            name: $user_model->name !== null ? (string) $user_model->name : null,
            last_name: $user_model->last_name !== null ? (string) $user_model->last_name : null,
            email: $user_model->email !== null ? (string) $user_model->email : null,
            username: $user_model->username !== null ? (string) $user_model->username : null,
            phone: $user_model->phone !== null ? (string) $user_model->phone : null,
            change_password: (bool) $user_model->change_password,
            role_id: (string) $user_model->role_id,
            role_name: isset($user_model->role_name) ? (string) $user_model->role_name : '',
            user_status_id: (int) $user_model->user_status_id,
            user_type_id: isset($user_model->user_type_id) ? (int) $user_model->user_type_id : 0,
            user_type_name: isset($user_model->user_type_name) ? (string) $user_model->user_type_name : null,
            company_id: isset($user_model->company_id) && $user_model->company_id !== null ? (int) $user_model->company_id : null,
            company_name: isset($user_model->company_name) ? (string) $user_model->company_name : null,
            company_commercial_name: isset($user_model->company_commercial_name) ? (string) $user_model->company_commercial_name : null,
            company_bussiness_name: isset($user_model->company_bussiness_name) ? (string) $user_model->company_bussiness_name : null,
            company_email: isset($user_model->company_email) ? (string) $user_model->company_email : null,
            company_primary_color: isset($user_model->company_primary_color) ? (string) $user_model->company_primary_color : null,
            company_secondary_color: isset($user_model->company_secondary_color) ? (string) $user_model->company_secondary_color : null,
            company_tertiary_color: isset($user_model->company_tertiary_color) ? (string) $user_model->company_tertiary_color : null
        );
    }

    /**
     * @param string $identifier
     * @return User|null
     */
    private function getUserByIdentifier(string $identifier): ?User
    {
        return $this->baseUserProfileQuery()
            ->when(
                filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false,
                fn($query) => $query->where('users.email', $identifier),
                fn($query) => $query->where('users.username', $identifier)
            )
            ->first();
    }

    /**
     * @param string $user_id
     * @return User|null
     */
    private function getUserById(string $user_id): ?User
    {
        return $this->baseUserProfileQuery()
            ->where('users.id', $user_id)
            ->first();
    }

    /**
     * @return Builder
     */
    private function baseUserProfileQuery(): Builder
    {
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.last_name',
                'users.phone',
                'users.email',
                'users.username',
                'users.session_attempts_mirror',
                'users.session_attempts',
                'users.password',
                'users.change_password',
                'users.user_status_id',
                'users.role_id',
                'roles.name as role_name',
                'user_types.id as user_type_id',
                'user_types.name as user_type_name',
                'companies.id as company_id',
                'companies.name as company_name',
                'companies.commercial_name as company_commercial_name',
                'companies.bussiness_name as company_bussiness_name',
                'companies.email as company_email',
                'companies.primary_color as company_primary_color',
                'companies.secondary_color as company_secondary_color',
                'companies.tertiary_color as company_tertiary_color',
            ])
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('user_types', 'roles.user_type_id', '=', 'user_types.id')
            ->leftJoin('companies', 'roles.company_id', '=', 'companies.id')
            ->whereNull('users.deleted_at');
    }

    /**
     * @param string $token
     * @param string $token_kind
     * @return stdClass
     */
    private function decodeTokenPayload(string $token, string $token_kind): stdClass
    {
        try {
            $payload = Token::decode($token);
        } catch (Throwable $throwable) {
            if ($token_kind === 'refresh') {
                throw new InvalidRefreshTokenException('Invalid refresh token.');
            }

            throw new InvalidAccessTokenException('Invalid access token.');
        }

        $token_not_before = (int) ($payload->nbf ?? 0);
        $token_expiration = (int) ($payload->exp ?? 0);
        $platform_type = (string) ($payload->{'platform-type'} ?? '');

        if ($token_not_before <= 0 || $token_expiration <= ClientCarbon::now()->timestamp || $platform_type === '') {
            if ($token_kind === 'refresh') {
                throw new InvalidRefreshTokenException('Expired refresh token.');
            }

            throw new InvalidAccessTokenException('Expired access token.');
        }

        return $payload;
    }

    /**
     * @param int $expiration_timestamp
     * @param string $jwtid
     * @param int $platform_type
     * @param string $ip
     * @return array<string, int|string>
     */
    private function buildTokenPayload(
        int $expiration_timestamp,
        string $jwtid,
        int $platform_type,
        string $ip
    ): array {
        return [
            'nbf' => ClientCarbon::now()->timestamp,
            'exp' => $expiration_timestamp,
            'url' => getFullDomain(),
            'tz' => (string) ClientCarbon::now()->getTimezone(),
            'ip' => $ip,
            'jwtid' => $jwtid,
            'platform-type' => (string) $platform_type,
        ];
    }

    /**
     * @return int
     */
    private function getAccessTtlMinutes(): int
    {
        $ttl = (int) env('JWT_TTL', self::ACCESS_TOKEN_TTL_MINUTES);
        return $ttl > 0 ? $ttl : self::ACCESS_TOKEN_TTL_MINUTES;
    }

    /**
     * @return int
     */
    private function getRefreshTtlMinutes(): int
    {
        $ttl = (int) env('JWT_REFRESH_TTL', self::REFRESH_TOKEN_TTL_MINUTES);
        return $ttl > 0 ? $ttl : self::REFRESH_TOKEN_TTL_MINUTES;
    }
}

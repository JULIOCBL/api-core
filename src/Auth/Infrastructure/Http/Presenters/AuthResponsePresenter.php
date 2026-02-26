<?php

namespace Src\Auth\Infrastructure\Http\Presenters;

use Src\Auth\Domain\ValueObjects\AuthSession;

/**
 * Presenter para respuestas de autenticación.
 */
class AuthResponsePresenter
{
    /**
     * @param AuthSession $auth_session
     * @return array<string, mixed>
     */
    public function presentSession(AuthSession $auth_session): array
    {
        $user = $auth_session->getUser();

        $response_data = [
            'access_token' => $auth_session->getAccessToken(),
            'refresh_token' => $auth_session->getRefreshToken(),
            'token_type' => $auth_session->getTokenType(),
            'expires_in' => $auth_session->getExpiresIn(),
            'refresh_expires_in' => $auth_session->getRefreshExpiresIn(),
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'last_name' => $user->getLastName(),
                'phone' => $user->getPhone(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'change_password' => $user->getChangePassword(),
                'role' => [
                    'id' => $user->getRoleId(),
                    'name' => $user->getRoleName(),
                ],
                'user_type' => [
                    'id' => $user->getUserTypeId(),
                    'name' => $user->getUserTypeName(),
                ],
            ],
            'permissions' => $auth_session->getPermissions(),
        ];

        if ($user->getCompanyId() !== null) {
            $response_data['user']['company'] = [
                'id' => $user->getCompanyId(),
                'name' => $user->getCompanyName(),
                'commercial_name' => $user->getCompanyCommercialName(),
                'bussiness_name' => $user->getCompanyBussinessName(),
                'email' => $user->getCompanyEmail(),
                'primary_color' => $user->getCompanyPrimaryColor(),
                'secondary_color' => $user->getCompanySecondaryColor(),
                'tertiary_color' => $user->getCompanyTertiaryColor(),
            ];
        }

        return $response_data;
    }
}

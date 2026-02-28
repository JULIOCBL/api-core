<?php

namespace Src\Core\Infrastructure\Support\Builders;

use Psr\Log\LogLevel;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Core\Infrastructure\Support\Helpers\UserHelper;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Builder para resolver company_id en el contexto autenticado actual.
 */
final class CompanyContextBuilder
{
    /**
     * Resuelve company_id para el request actual.
     * Para ROOT se toma desde request (input/query/route).
     * Para el resto se toma desde request()->user()->role_company_id.
     *
     * @return int
     */
    public function build(): int
    {
        if (isRoot()) {
            return $this->resolveRootCompanyId();
        }

        return $this->resolveUserCompanyId();
    }

    /**
     * Obtiene company_id enviado por un usuario ROOT.
     *
     * @return int
     */
    private function resolveRootCompanyId(): int
    {
        $company_id = request()->input('company_id');
        if ($company_id === null || $company_id === '') {
            $company_id = request()->query('company_id');
        }
        if ($company_id === null || $company_id === '') {
            $company_id = request()->route('company_id');
        }

        if ($company_id === null || $company_id === '') {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.missing_company_context.title'),
                __('auth::session.missing_company_context.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_MISSING_COMPANY_CONTEXT_1013
            );
        }

        if (!is_numeric($company_id)) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.invalid_company_context.title'),
                __('auth::session.invalid_company_context.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_INVALID_COMPANY_CONTEXT_1014
            );
        }

        return (int) $company_id;
    }

    /**
     * Obtiene company_id del usuario autenticado no ROOT.
     *
     * @return int
     */
    private function resolveUserCompanyId(): int
    {
        $auth_user = UserHelper::user();
        $company_id = $auth_user->role_company_id ?? null;

        if ($company_id === null || $company_id === '' || !is_numeric($company_id)) {
            throw new JsonException(
                LogLevel::WARNING,
                __('auth::session.missing_company_context.title'),
                __('auth::session.missing_company_context.description'),
                HttpResponse::HTTP_BAD_REQUEST,
                '',
                ErrorCodes::AUTH_MISSING_COMPANY_CONTEXT_1013
            );
        }

        return (int) $company_id;
    }
}

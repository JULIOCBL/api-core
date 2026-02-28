<?php

namespace Src\Auth\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Log\LogLevel;
use Src\Core\Infrastructure\Exceptions\JsonException;
use Src\Shared\Infrastructure\Errors\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Middleware para exigir company_id cuando el usuario autenticado es ROOT.
 */
class RequireRootCompanyIdMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!isRoot()) {
            return $next($request);
        }

        $company_id = $request->input('company_id');
        if ($company_id === null || $company_id === '') {
            $company_id = $request->query('company_id');
        }
        if ($company_id === null || $company_id === '') {
            $company_id = $request->route('company_id');
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

        return $next($request);
    }
}

<?php

namespace Src\Core\Infrastructure\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Src\Auth\Infrastructure\Http\Middleware\Authenticate;
use Src\Auth\Infrastructure\Http\Middleware\RedirectIfAuthenticated;
use Src\Core\Infrastructure\Http\Middleware\LanguageMiddleware;
use Src\Core\Infrastructure\Http\Middleware\TrimStrings;
use Src\Core\Infrastructure\Http\Middleware\TrustProxies;
use Src\Core\Infrastructure\Providers\HtmlMinifyMiddleware;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        // \Fruitcake\Cors\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        LanguageMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            HtmlMinifyMiddleware::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // Autenticación HTTP básica mediante encabezados Authorization.
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        // Establece encabezados de caché HTTP en la respuesta (como max-age o etag).
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        // Valida que la URL firmada no haya sido modificada (usado en links seguros).
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // Verifica que el correo electrónico del usuario esté verificado antes de acceder a ciertas rutas.
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

    ];
}

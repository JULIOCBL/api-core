<?php


namespace Src\Core\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = is_array(config('app.supported_languages')) ? config('app.supported_languages') : ['es'];

        if ($request->header('Accept-Language')) {
            if (in_array($request->header('Accept-Language'), $locales)) {
                App::setLocale($request->header('Accept-Language'));
            } else {
                App::setLocale('es');
            }
        } else {
            App::setLocale('es');
        }

        return $next($request);
    }
}

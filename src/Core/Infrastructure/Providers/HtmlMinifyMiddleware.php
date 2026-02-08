<?php

namespace Src\Core\Infrastructure\Providers;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use voku\helper\HtmlMin;

class HtmlMinifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $response->headers->has('Content-Type') &&
            str_contains($response->headers->get('Content-Type'), 'text/html')
        ) {
            $htmlMin = new HtmlMin();

            // Opcional: configura según tus necesidades
            $htmlMin->doOptimizeAttributes(true);
            $htmlMin->doRemoveComments(true);
            $htmlMin->doSumUpWhitespace(true);
            $htmlMin->doRemoveWhitespaceAroundTags(true);
            $htmlMin->doOptimizeViaHtmlDomParser(true);
            $htmlMin->doRemoveOmittedHtmlTags(true);

            $minified = $htmlMin->minify($response->getContent());
            $response->setContent($minified);
        }

        return $response;
    }
}

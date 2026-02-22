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
            $html_min = new HtmlMin();

            // Opcional: configura según tus necesidades
            $html_min->doOptimizeAttributes(true);
            $html_min->doRemoveComments(true);
            $html_min->doSumUpWhitespace(true);
            $html_min->doRemoveWhitespaceAroundTags(true);
            $html_min->doOptimizeViaHtmlDomParser(true);
            $html_min->doRemoveOmittedHtmlTags(true);

            $minified = $html_min->minify($response->getContent());
            $response->setContent($minified);
        }

        return $response;
    }
}

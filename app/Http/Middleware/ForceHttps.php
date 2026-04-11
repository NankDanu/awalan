<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Force HTTPS scheme when the request is behind a proxy sending HTTPS headers.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $forwardedProto = $request->headers->get('x-forwarded-proto');
        $forwardedSsl = $request->headers->get('x-forwarded-ssl');

        if ($forwardedProto && strtolower($forwardedProto) === 'https') {
            URL::forceScheme('https');
            $request->server->set('HTTPS', 'on');
        } elseif ($forwardedSsl && strtolower($forwardedSsl) === 'on') {
            URL::forceScheme('https');
            $request->server->set('HTTPS', 'on');
        }

        return $next($request);
    }
}

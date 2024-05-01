<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ForceJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptHeader = $request->headers->get('Accept');
        if (! Str::contains($acceptHeader, 'application/json')) {
            $newAcceptHeader = 'application/json';
            if ($acceptHeader) {
                $newAcceptHeader .= "/$acceptHeader";
            }
            $request->headers->set('Accept', $newAcceptHeader);
        }

        return $next($request);
    }
}

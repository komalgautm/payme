<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TraceIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $traceId = (string) \Ramsey\Uuid\Uuid::uuid4();

        // Add the Trace-Id header to the request
        $request->headers->set('Trace-Id', $traceId);

        // Continue with the request
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class csrf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->method(), ['POST','PUT','PATCH','DELETE'])) {
            $cookie = $request->cookie('csrf_token');
            $header = $request->header('X-CSRF-TOKEN');

            if (!$cookie || !$header || !hash_equals($cookie, $header)) {
                return response()->json(['error' => 'CSRF'], 419);
            }
        }

        return $next($request);
    }
}

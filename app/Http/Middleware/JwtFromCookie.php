<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     public function handle(Request $request, Closure $next)
     {

        if ($request->hasCookie('token')) {
            $request->headers->set(
                'Authorization',
                'Bearer '.$request->cookie('token')
            );
        }

         if (!$request->hasCookie('token')) {
             return response()->json(['message' => 'Unauthenticated'], 401);
         }

         try {
             JWTAuth::setToken($request->cookie('token'))->getPayload();
         } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token expired'], 401);

         } catch (\Exception $e) {
             return response()->json(['message' => 'Unauthenticated'], 401);
         }

         return $next($request);
     }

}

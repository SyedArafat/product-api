<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        }
        catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                return response()->json(['message' => 'Token is Invalid', 'code' => 401]);

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
                return response()->json(['message' => 'Token is Expired', 'code' => 402]);

            return response()->json(['message' => 'Token not found', 'code' => 403]);

        }

        return $next($request);
    }
}

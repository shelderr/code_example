<?php

namespace App\Http\Middleware\Telescope;

use Auth;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppendToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->route()->getName() != 'telescope') {
            return $response;
        }

        if ($request->cookie('token')) {
            return $response;
        }

        if (empty($token = $request->input('token'))) {
            return $response;
        }

        $payload = JWTAuth::setToken($token)->getPayload();
        $exp = $payload->get('exp');
        $minutes = floor(($exp - time()) / 60);

        $response = $next($request);
        $response->withCookie(cookie('token', $token, $minutes));

        return $response;

    }
}

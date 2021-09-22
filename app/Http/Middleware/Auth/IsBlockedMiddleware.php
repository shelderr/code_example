<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\UnauthorizedException;
use App\Services\Base\BaseAppGuards;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IsBlockedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     * @throws \App\Exceptions\Http\UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        $user  = Auth::guard(BaseAppGuards::USER)->user();

        if (Auth::guard(BaseAppGuards::ADMIN)->check()) {
            $user = Auth::guard(BaseAppGuards::ADMIN)->user();
        }

        if ($user && ! is_null($user)) {
            if ($user->blocked) {
                $token = $request->bearerToken();
                \Illuminate\Support\Facades\Auth::setToken($token)->invalidate();
                throw new UnauthorizedException(ErrorMessages::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
            }
        }


        return $next($request);
    }
}

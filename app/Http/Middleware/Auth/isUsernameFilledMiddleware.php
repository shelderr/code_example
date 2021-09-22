<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Auth\Social\UsernameNotFoundException;
use App\Exceptions\ErrorMessages;
use App\Services\Base\BaseAppGuards;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class isUsernameFilledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     * @throws \App\Exceptions\Auth\Social\UsernameNotFoundException
     */
    public function handle(Request $request, Closure $next)
    {
        $user = \Auth::guard(BaseAppGuards::USER)->user();

        if ($request->getRequestUri() == '/api/user/settings/change_username' ||
            $request->getRequestUri() == '/api/user/login' ||
            $request->getRequestUri() == '/api/user/logout'
        ) {
            return $next($request);
        }

        if ($user) {
            if ($user->user_name == null) {
                throw new UsernameNotFoundException(ErrorMessages::USERNAME_NOT_EXIST, Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}

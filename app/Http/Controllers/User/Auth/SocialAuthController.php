<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Requests\User\Auth\FacebookAuthRequest;
use App\Services\User\Auth\FacebookAuthService;
use App\Services\User\Auth\GoogleAuthService;
use App\Http\Requests\User\Auth\GoogleAuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SocialAuthController extends Controller
{
    /**
     * @param \App\Http\Requests\User\Auth\GoogleAuthRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function googleLogin(GoogleAuthRequest $request): \Illuminate\Http\Response
    {
        $user = resolve(GoogleAuthService::class)->login($request->validated()['token']);

        return \response($user, \Illuminate\Http\Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\User\Auth\FacebookAuthRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookLogin(FacebookAuthRequest $request): Response
    {
        $user = resolve(FacebookAuthService::class)->login($request->validated()['token'] ?? null);

        return \response($user, Response::HTTP_OK);
    }
}

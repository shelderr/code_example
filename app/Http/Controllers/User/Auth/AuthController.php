<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Exceptions\ErrorMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\GoogleAuthRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Services\User\Auth\GoogleAuthService;
use App\Services\UserService;
use App\Traits\FormatsErrorResponse;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Shipper\Auth
 */
class AuthController extends Controller
{
    use FormatsErrorResponse;

    private UserService $service;

    private GoogleAuthService $gAuthService;

    public function __construct(UserService $service, GoogleAuthService $gAuthService)
    {
        $this->service = $service;
        $this->middleware(['auth:user'])->except(
            'login',
            'register',
            'resentEmailConfirmationToken',
            'googleLogin'
        );
        $this->gAuthService = $gAuthService;
    }

    /**
     * @param \App\Http\Requests\User\Auth\LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \App\Exceptions\Model\NotFoundException
     * @throws \ReflectionException
     */
    public function login(LoginRequest $request): Response
    {
        $input = $request->validated();

        ['confirmed' => $confirmed, 'email' => $email] = $this->service->isEmailConfirmed('email', $input['email']);

        if (! $confirmed) {
            return response(
                [
                    'userData' => compact('email'),
                    'errors'   => [$this->formatErrors(ErrorMessages::EMAIL_NOT_CONFIRMED)],
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        ['token' => $token, 'user_data' => $userData] =
            $this->service
                ->auth($input['email'], $input['password'], 'email', $input['remember'] ?? false, $input['totp'] ?? '');

        $userData = new UserDataResource($userData);

        $this->service->userActivity($request, $userData);

        return response(compact('token', 'userData'), Response::HTTP_OK);
    }

    /**
     * @param RegisterRequest $request
     *
     * @return Response
     * @throws \Throwable
     */
    public function register(RegisterRequest $request): Response
    {
        return response($this->service->register($request->validated()), Response::HTTP_OK);
    }

    public function googleLogin(GoogleAuthRequest $request): Response
    {
        $token = $this->gAuthService->login($request->validated()['token']);

        return \response(compact('token'), Response::HTTP_OK);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function refreshToken(Request $request)
    {
        return response($this->service->tokenRefresh($request), Response::HTTP_OK);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout(): Response
    {
        $this->service->logout();

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Application\ApplicationException
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \ReflectionException
     */
    public function resentEmailConfirmationToken(Request $request): Response
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
            ]
        );

        $this->service->resendEmail($request->email);

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function changePassword(Request $request): Response
    {
        $data = $request->validate(
            [
                'currentPassword' => ['required', 'string'],
                'newPassword'     => ['required', 'string', 'confirmed'],
            ]
        );

        $this->service->changePassword($data, auth()->id());

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ResetPasswordRequest;
use App\Http\Requests\User\Auth\ForgotPasswordRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ForgotPasswordController
 *
 * @package App\Http\Controllers\Carrier\Auth
 */
class ForgotPasswordController extends Controller
{
    /**
     * @var UserService
     */
    private UserService $service;

    /**
     * ForgotPasswordController constructor.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function sendResetTokenEmail(ForgotPasswordRequest $request): Response
    {
        $response = $this->service->sendResetToken($request->input('email', ''));

        return $response ? response($response, Response::HTTP_CREATED) : response()->noContent(Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function checkToken(Request $request): Response
    {
        $this->service->checkEmailToken($request->input('token', ''));

        return response()->noContent(Response::HTTP_ACCEPTED);
    }

    /**
     * @param ResetPasswordRequest $request
     *
     * @return Response
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $this->service->resetPassword($request->input('token', ''), $request->input('password', ''));

        return response()->noContent(Response::HTTP_ACCEPTED);
    }
}

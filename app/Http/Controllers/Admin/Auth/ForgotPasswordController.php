<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ChangePasswordRequest;
use App\Http\Requests\Admin\Auth\ForgotPasswordRequest;
use App\Http\Requests\Admin\Auth\ResetPasswordRequest;
use App\Services\AdminService;
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
     * @var \App\Services\AdminService
     */
    private AdminService $service;

    /**
     * ForgotPasswordController constructor.
     *
     * @param \App\Services\AdminService $service
     */
    public function __construct(AdminService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:admin'])->except('sendNewPassword', 'sendLinkEmail', 'resetPassword', 'checkToken');
    }

    /**
     * @param ForgotPasswordRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function sendNewPassword(ForgotPasswordRequest $request): Response
    {
        $response = $this->service->sendNewPassword($request->input('email', ''));

        return $response ? response($response, Response::HTTP_CREATED) : response()->noContent(Response::HTTP_CREATED);
    }

    /**
     * @param \App\Http\Requests\Admin\Auth\ChangePasswordRequest $request
     *
     * @return Response
     */
    public function changePassword(ChangePasswordRequest $request): Response
    {
        $this->service->setNewPassword(auth()->id(), $request->input('password', ''));

        return response()->noContent(Response::HTTP_ACCEPTED);
    }

    /**
     * @param ForgotPasswordRequest $request
     *
     * @return Response
     */
    public function sendLinkEmail(ForgotPasswordRequest $request): Response
    {
        $response = $this->service->sendLinkEmail($request->input('email', ''));

        return $response ? response($response, Response::HTTP_ACCEPTED) :
            response()->noContent(Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function checkToken(Request $request): Response
    {
        $this->service->checkEmailToken($request->input('token', ''));

        return response()->noContent(Response::HTTP_ACCEPTED);
    }

    /**
     * @param \App\Http\Requests\Admin\Auth\ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $this->service->resetPassword($request->input('token'), $request->input('password'));
        
        return response()->noContent(Response::HTTP_ACCEPTED);
    }
}

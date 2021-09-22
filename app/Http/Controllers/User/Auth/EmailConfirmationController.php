<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class EmailConfirmationController
 *
 * @package App\Http\Controllers\Auth
 */
class EmailConfirmationController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->middleware('throttle:60,1')->only('confirmEmail');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function confirmEmail(Request $request): Response
    {
        $request->validate(
            [
                'token' => ['required', 'string', 'size:' . User::CONFIRM_TOKEN],
            ]
        );

        $this->service->confirmEmail($request->token);

        return response()->noContent(Response::HTTP_ACCEPTED);
    }
}

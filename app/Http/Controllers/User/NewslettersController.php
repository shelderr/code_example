<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Settings\Newsletters\NewsSubscribingRequest;
use App\Models\User;
use App\Services\Base\BaseAppGuards;
use App\Services\User\MailchimpSubscribeService;
use App\Services\User\SubscribeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewslettersController extends Controller
{
    private ?User $user;

    private SubscribeService $service;

    public function __construct()
    {
        $this->user    = auth()->guard(BaseAppGuards::USER)->user();
        $this->service = resolve(SubscribeService::class);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function mailchimpSubscribe(Request $request): \Illuminate\Http\Response
    {
        $this->validate(
            $request,
            [
                'email' => ['required', 'regex:' . User::EMAIL_REGEX, 'max:255', 'email:rfc,dns'],
            ]
        );

        resolve(MailchimpSubscribeService::class)->subscribe($request->email);

        return response()->noContent();
    }

    public function newsletterSubscribeSwitch(NewsSubscribingRequest $request): Response
    {
        $user = $this->service->subscribeNewsletter($request->validated()['news_subscription']);

        return response(compact('user'), Response::HTTP_OK);
    }

    public function systemNewsletterSubscribeSwitch(): Response
    {
        $user = $this->service->systemNotificationsSwitch();

        return response(compact('user'), Response::HTTP_OK);
    }
}

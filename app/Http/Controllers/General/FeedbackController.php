<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Feedback\SendFeedbackRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\General\FeedbackService;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    private FeedbackService $service;

    public function __construct()
    {
        $this->service = resolve(FeedbackService::class);
        $this->middleware('auth:' . BaseAppGuards::USER)->only('sendFeedback');
    }

    public function sendFeedback(SendFeedbackRequest $request): Response
    {
        $data     = $request->validated();
        $feedback = $this->service->sendFeedback($data);

        return response(compact('feedback'), Response::HTTP_OK);
    }
}

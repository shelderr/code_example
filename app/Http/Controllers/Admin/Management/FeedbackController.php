<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Services\Base\BaseAppGuards;
use App\Services\General\FeedbackService;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    private FeedbackService $service;

    public function __construct()
    {
        $this->service = resolve(FeedbackService::class);
        $this->middleware('auth:'. BaseAppGuards::ADMIN);
    }

    public function index()
    {
        return $this->service->index(10);
    }
}

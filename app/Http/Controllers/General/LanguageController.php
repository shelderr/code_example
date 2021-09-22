<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\CountryIndexRequest;
use App\Services\General\LanguageService;

use Illuminate\Http\Response;

class LanguageController extends Controller
{
    private LanguageService $service;

    public function __construct()
    {
        $this->service = resolve(LanguageService::class);
    }

    public function index(CountryIndexRequest $request)
    {
        $countries = $this->service->index();

        return response(compact('countries'), Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\CountryIndexRequest;
use App\Services\General\CountryService;
use Illuminate\Http\Response;

class CountryController extends Controller
{
    private CountryService $service;

    public function __construct()
    {
        $this->service = resolve(CountryService::class);
    }

    public function index(CountryIndexRequest $request)
    {
        $countries = $this->service->index();

        return response(compact('countries'), Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\GoogleMaps\SearchLocationRequest;
use App\Services\General\GoogleMapsService;
use Illuminate\Http\Response;

class GoogleMapsController extends Controller
{
    private GoogleMapsService $service;

    public function __construct()
    {
        $this->service = resolve(GoogleMapsService::class);
    }

    public function searchLocation(SearchLocationRequest $request): Response
    {
        $googleResponse = $this->service->searchLocation($request->validated()['location']);

        return response(compact('googleResponse'), Response::HTTP_OK);
    }
}

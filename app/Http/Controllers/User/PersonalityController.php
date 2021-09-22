<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Settings\Personality\SendVerificationRequest;
use App\Services\Person\PersonService;
use Illuminate\Http\Request;

class PersonalityController extends Controller
{
    private PersonService $service;

    public function __construct()
    {
        $this->service = resolve(PersonService::class);
    }

    /**
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function sendVerificationRequest(SendVerificationRequest $request): \Illuminate\Http\Response
    {
        $data = $request->validated();
        
        $this->service->linkUserToPerson($data['person_id'], $data['facebook_url']);
        
        return response()->noContent();
    }
}

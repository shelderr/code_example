<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\AddAuthHelper;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use WithFaker, AddAuthHelper;

    private function getUserData(): array
    {
        $email    = $this->faker->email;
        $password = 'SDasdasbj213/213.';
        $username = Str::random(7) . '2-';

        return [
            'email'                     => $email,
            'email_confirmation'        => $email,
            'user_name'                 => $username,
            'password'                  => $password,
            'password_confirmation'     => $password,
            'google_recaptcha_response' => '000000',
            'news_subscription'         => false,
        ];
    }

    private function confirmEmail(TestResponse $response)
    {
        $token = $response->decodeResponseJson()['email_confirm_token'];

        $response = $this->putJson(
            'api/user/email_confirmation', ['token' => (string) $token]
        );
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }

    public function testRegistration(): void
    {
        $response = $this->postJson('api/user/register', []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $userData = $this->getUserData();
        $response = $this->postJson('api/user/register', $userData);
        $response->assertOk();

        $this->assertDatabaseHas(
            'users', [
                       'email' => $userData['email'],
                   ]
        );
    }

    public function testLoginLogout(): void
    {
        $responseReg = $this->postJson('api/user/register', []);
        $responseReg->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $userData    = $this->getUserData();
        $responseReg = $this->postJson('api/user/register', $userData);
        $responseReg->assertOk();
        $this->assertDatabaseHas(
            'users', [
                       'email' => $userData['email'],
                   ]
        );

        $this->confirmEmail($responseReg);

        $responseLogin = $this->postJson(
            'api/user/login', [
                                'email'                => $userData['email'],
                                'password'             => $userData['password'],
                                'remember'             => false,
                                'g_recaptcha_response' => '000000',
                            ]
        );

        $responseLogin->assertOk();

        $responseLogin->assertJsonStructure(
            [
                'token',
                'userData' => [
                    'id',
                    'email',
                    'user_name',
                    'last_login',
                    'last_activity',
                ],
            ]
        );
        $responseLogout = $this->postJson(
            'api/user/logout', [], $this->makeHeader($responseLogin->decodeResponseJson()['token'])
        );
        $responseLogout->assertStatus(Response::HTTP_NO_CONTENT);
    }
}

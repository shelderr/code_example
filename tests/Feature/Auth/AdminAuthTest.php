<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\AddAuthHelper;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use WithFaker, AddAuthHelper, RefreshDatabase;

    private function getAdminData(): array
    {
        return [
            'email'              => 'admin@gmail.com',
            'password'           => 'SDasdasbj213/213.',
            'remember'           => false,
            'gRecaptchaResponse' => '000000',
        ];
    }

    private function adminLogin()
    {
        $userData      = $this->getAdminData();
        $responseLogin = $this->postJson(
            'api/admin/login',
            [
                'email'                => $userData['email'],
                'password'             => $userData['password'],
                'remember'             => false,
                'g_recaptcha_response' => '000000',
            ]
        );

        // dd($responseLogin->getContent());
        $responseLogin->assertOk();
        $responseLogin->assertJsonStructure(
            [
                'token',
                'userData' => [
                    'email',
                    'firstName',
                    'lastName',
                    'userName',
                    'lastLogin',
                    'authy2fa_enabled',
                ],
            ]
        );

        return $responseLogin;
    }

    public function testLoginLogout(): void
    {
        $responseLogin  = $this->adminLogin();
        $responseLogout = $this->postJson(
            'api/admin/logout', [], $this->makeHeader($responseLogin->decodeResponseJson()['token'])
        );
        $responseLogout->assertStatus(Response::HTTP_NO_CONTENT);
    }
    /**
     * public function testResetPassword()
     * {
     * $userData      = $this->getAdminData();
     * $responseReset = $this->postJson(
     * 'api/admin/forgot_password/email',
     * [
     * 'email' => $userData['email'],
     * ]
     * );
     *
     * $responseReset->assertStatus(Response::HTTP_ACCEPTED);
     *
     * $newPass = 'SDasdasbj213/213.asd';
     *
     * $responseChangePass = $this->putJson(
     * 'api/admin/forgot_password/reset',
     * [
     * 'token'                 => $responseReset->decodeResponseJson()['token'],
     * 'password'              => $newPass,
     * 'password_confirmation' => $newPass,
     * ]
     * );
     *
     * $responseChangePass->assertStatus(Response::HTTP_ACCEPTED);
     * }
     *
     * public function testChangePassword()
     * {
     * $userData      = $this->getAdminData();
     * $loginResponse = $this->adminLogin();
     *
     * $newPass = 'SDasdasbj213/213.asd';
     *
     * $responseChange = $this->putJson(
     * 'api/admin/change_password',
     * [
     * 'current_password'      => $userData['password'],
     * 'password'              => $newPass,
     * 'password_confirmation' => $newPass,
     * ], $this->makeHeader($loginResponse->decodeResponseJson()['token'])
     * );
     *
     * $responseChange->assertStatus(Response::HTTP_ACCEPTED);
     * } **/
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Str;
use Tests\Helpers\AddAuthHelper;
use Tests\TestCase;

class UserProfileTest extends TestCase
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
        ];
    }

    private function register(array $userData): TestResponse
    {
        $responseReg = $this->postJson('api/user/register', $userData);
        $responseReg->assertOk();
        $this->assertDatabaseHas(
            'users', [
                       'email' => $userData['email'],
                   ]
        );

        return $responseReg;
    }

    private function login(string $email, string $password): TestResponse
    {
        return $this->postJson(
            'api/user/login',
            [
                'email'                => $email,
                'password'             => $password,
                'remember'             => false,
                'g_recaptcha_response' => '000000',
            ]
        );
    }

    private function confirmEmail(TestResponse $response)
    {
        $token    = $response->decodeResponseJson()['email_confirm_token'];
        $response = $this->putJson(
            'api/user/email_confirmation', ['token' => (string) $token]
        );
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }

    private function passwordReset(TestResponse $response)
    {
        $token = $response->decodeResponseJson()['password_reset_token'];

        $password = $this->faker->password . './';
        $response = $this->putJson(
            'api/user/password', [
                                   'token'                 => $token,
                                   'password'              => $password,
                                   'password_confirmation' => $password,
                               ]
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPasswordRecover()
    {
        $userData    = $this->getUserData();
        $responseReg = $this->register($userData);
        $this->confirmEmail($responseReg);

        $responseRecovery = $this->postJson('api/user/password/email', ['email' => $userData['email']]);
        $responseRecovery->assertStatus(Response::HTTP_CREATED);
    }

    public function testChangePhoto()
    {
        $userData    = $this->getUserData();
        $responseReg = $this->register($userData);

        $this->confirmEmail($responseReg);

        $responseLogin = $this->login($userData['email'], $userData['password']);

        $responseEdit  = $this->postJson(
            'api/user/settings/upload_photo', [
            'photo'      => UploadedFile::fake()->image('avatar.png', User::MAX_WIDTH, User::MAX_HEIGHT),
        ], $this->makeHeader($responseLogin->decodeResponseJson()['token'])
        );

        $responseEdit->assertStatus(Response::HTTP_OK);
    }

    public function testChangePassword()
    {
        $userData    = $this->getUserData();
        $responseReg = $this->register($userData);
        $this->confirmEmail($responseReg);
        $responseLogin = $this->login($userData['email'], $userData['password']);

        $newPass = 'SDasdasbj213/213.asd';

        $responseChangePass = $this->putJson(
            'api/user/settings/change_password', [
            'current_password'          => $userData['password'],
            'new_password'              => $newPass,
            'new_password_confirmation' => $newPass,
        ], $this->makeHeader($responseLogin->decodeResponseJson()['token'])
        );

        $responseChangePass->assertStatus(Response::HTTP_NO_CONTENT);

        $responseLogout = $this->postJson(
            'api/user/logout', [], $this->makeHeader($responseLogin->decodeResponseJson()['token'])
        );

        $responseLogout->assertStatus(Response::HTTP_NO_CONTENT);
        $responseLoginChanged = $this->login($userData['email'], $newPass);

        $responseLoginChanged->assertOk();
    }

   /* public function testChangePhone()
    {
        $userData    = $this->getUserData();
        $responseReg = $this->register($userData);
        $this->confirmEmail($responseReg);
        $responseLogin = $this->login($userData['email'], $userData['password']);

        $responsePhone = $this->put(
            'api/user/settings/change_phone', [
            'phone' => $this->faker->e164PhoneNumber,
        ], $this->makeHeader($responseLogin->decodeResponseJson()['token'])
        );

        $responsePhone->assertStatus(Response::HTTP_NO_CONTENT);
    }*/

}

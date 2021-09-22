<?php

namespace Tests\Feature\Persons\User;

use App\Models\Persons;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\AddAuthHelper;
use Tests\TestCase;

class PersonsTest extends TestCase
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

    /**
     * Fake event generating
     *
     * @return array
     */
    private function getPerson()
    {
        $birthDay = Carbon::now()->subYears(rand(1, 50))->format(Persons::DATE_FORMAT);

        return [
            'name'        => $this->faker->name(),
            'native_name' => $this->faker->name(),
            'stage_name'  => $this->faker->userName,
            'bio'         => $this->faker->text(),
            'image'       => UploadedFile::fake()
                ->image('poster.png', Persons::PHOTO_WIDTH, Persons::PHOTO_HEIGHT),
            'is_deceased' => false,
            'birth_date'  => $birthDay,
            'birth_place' => $this->faker->city(),
            'countries'   => [1, 2, 3],
        ];
    }

    private function bearer(): string
    {
        $userData    = $this->getUserData();
        $responseReg = $this->register($userData);

        $this->confirmEmail($responseReg);

        $responseLogin = $this->login($userData['email'], $userData['password']);

        return $responseLogin->decodeResponseJson()['token'];
    }

    //TESTS

    public function testIndex()
    {
        $indexResponse = $this->postJson('/api/persons');
        $indexResponse->assertOk();
    }

    public function testIndexSorting()
    {
        $indexResponse = $this->postJson(
            '/api/persons', [
                              'sorting' => [
                                  'order'        => 'asc',
                                  'alphabetical' => 'A',
                              ],
                          ]
        );

        $indexResponse->assertOk();
    }

    public function testCreatePerson()
    {
        $createResponse = $this->postJson(
            '/api/persons/create', $this->getPerson(), $this->makeHeader($this->bearer())
        );

        $createResponse->assertOk();
    }

    public function testUpdatePerson()
    {
        $createResponse = $this->postJson(
            '/api/persons/create', $this->getPerson(), $this->makeHeader($this->bearer())
        );

        $createResponse->assertOk();
        $personId = $createResponse->decodeResponseJson()['person']['id'];

        $updateResponse = $this->postJson(
            "/api/persons/$personId/update", $this->getPerson()
        );

        $updateResponse->assertOk();
    }
}



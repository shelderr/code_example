<?php

namespace Tests\Feature\Events\User;

use App\Exceptions\Http\BadRequestException;
use App\Models\Events\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Str;
use Tests\Helpers\AddAuthHelper;
use Tests\TestCase;

class EventsTest extends TestCase
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
     * @param string $type
     *
     * @return array
     * @throws \App\Exceptions\Http\BadRequestException
     */
    private function getEvent(string $type): array
    {
        if (! in_array($type, Event::ALL_EVENTS)) {
            throw new BadRequestException('invalid event type');
        }

        $startDate = Carbon::now()->subYears(rand(1, 50))->format(Event::DATE_FORMAT);
        $endDate   = Carbon::now()->addDays(rand(1, 365))->format(Event::DATE_FORMAT);

        return [
            'title'               => $this->faker->title(),
            'type'                => $type,
            'is_active'           => (bool) random_int(0, 1),
            'poster'              => UploadedFile::fake()->image(
                'poster.png', Event::POSTER_WIDTH, Event::POSTER_HEIGHT
            ),
            'description'         => $this->faker->realText(),
            'start_date'          => $startDate,
            'end_date'            => $endDate,
            'company_name'        => $this->faker->company,
            'language_id'         => random_int(1, 125),
            'countries_created'   => [1, 2, 3],
            'countries_presented' => [1, 2, 3],
            'producer_id'         => 1,
            'president_id'        => 2,
            'year_established'    => $startDate,
            'is_television'       => (bool) random_int(0, 1),
            'tickets_url'         => $this->faker->url,
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

    public function testIndex()
    {
        $indexResponse = $this->postJson('/api/events', ['type' => 'show']);
        $indexResponse->assertOk();
    }

    public function testCreateShow()
    {
        $event              = $this->getEvent('show');
        $responseCreateShow = $this->postJson(
            '/api/events/create', $event,
            $this->makeHeader($this->bearer())
        );

        $responseCreateShow->assertOk();

        $this->assertDatabaseHas(
            'events', [
                        'title' => $event['title'],
                        'type'  => $event['type'],
                    ]
        );
    }

    public function testCreateEvent()
    {
        $event              = $this->getEvent('event');
        $responseCreateShow = $this->postJson(
            '/api/events/create', $event,
            $this->makeHeader($this->bearer())
        );

        $responseCreateShow->assertOk();

        $this->assertDatabaseHas(
            'events', [
                        'title' => $event['title'],
                        'type'  => $event['type'],
                    ]
        );
    }

    public function testUpdateEvent()
    {
        $event               = $this->getEvent('event');
        $responseCreateEvent = $this->postJson(
            '/api/events/create', $event,
            $this->makeHeader($this->bearer())
        );

        $responseCreateEvent->assertOk();

        $this->assertDatabaseHas(
            'events', [
                        'title' => $event['title'],
                        'type'  => $event['type'],
                    ]
        );

        $id             = $responseCreateEvent->decodeResponseJson()['event']['id'];
        $updateResponse = $this->postJson("/api/events/$id/update", $this->getEvent('event'));

        $updateResponse->assertOk();
    }

    public function testApplaud()
    {
        $event               = $this->getEvent('event');
        $responseCreateEvent = $this->postJson(
            '/api/events/create', $event,
            $this->makeHeader($this->bearer())
        );

        $responseCreateEvent->assertOk();

        $this->assertDatabaseHas(
            'events', [
                        'title' => $event['title'],
                        'type'  => $event['type'],
                    ]
        );

        $id              = $responseCreateEvent->decodeResponseJson()['event']['id'];
        $applaudResponse = $this->postJson("/api/events/$id/applaud", ['rating' => random_int(1, 10)]);

        $applaudResponse->assertOk();
    }

}

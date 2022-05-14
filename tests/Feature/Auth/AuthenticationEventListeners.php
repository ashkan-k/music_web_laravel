<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\UserVerificationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationEventListeners extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_registration_verify_code()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $data = [
            'first_name' => 'ashkan',
            'last_name' => 'karimi',
            'email' => 'ashkan@gmail.com',
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 1);

        Notification::assertSentTo(User::query()->whereEmail('ashkan@gmail.com')->first(), UserVerificationMail::class);
    }
}

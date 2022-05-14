<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Authentication extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
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
    }

    public function test_login()
    {
        $user = User::factory()->create();
        $user->password = Hash::make('123');
        $user->save();

        $data = [
            'email' => $user->email,
            'password' => '123',
            'remember_me' => true
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user, 'web');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_register_first_name_validation()
    {
        $data = [
            'first_name' => '',
            'last_name' => 'karimi',
            'email' => 'ashkan@gmail.com',
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['first_name']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////
        ///
        $data = [
            'first_name' => 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
            'last_name' => 'karimi',
            'email' => 'ashkan@gmail.com',
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['first_name']);
        $response->assertStatus(302);
    }

    public function test_register_email_validation()
    {
        $data = [
            'first_name' => 'dfsf',
            'last_name' => 'karimi',
            'email' => '',
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////
        ///
        $data = [
            'first_name' => 'ddd',
            'last_name' => 'karimi',
            'email' => 'ashkan@',
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////
        ///
        $user = User::factory()->create();
        $data = [
            'first_name' => 'ddd',
            'last_name' => 'karimi',
            'email' => $user->email,
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);
    }

    public function test_register_mobile_validation()
    {
        $data = [
            'first_name' => 'dfsf',
            'last_name' => 'karimi',
            'email' => 'dsfs@gmail.com',
            'phone' => '',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['phone']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////

        $data = [
            'first_name' => 'dddd',
            'last_name' => 'karimi',
            'email' => 'dsfs@gmail.com',
            'phone' => '09444',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['phone']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////

        $data = [
            'first_name' => 'dddd',
            'last_name' => 'karimi',
            'email' => 'dsfs@gmail.com',
            'phone' => '09396988720555555',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['phone']);
        $response->assertStatus(302);
    }

    public function test_register_password_validation()
    {
        $data = [
            'first_name' => 'dfsf',
            'last_name' => 'karimi',
            'email' => 'dsfs@gmail.com',
            'phone' => '09396988720',
            'password' => '',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['password']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////

        $data = [
            'first_name' => 'dddd',
            'last_name' => 'karimi',
            'email' => 'dsfs@gmail.com',
            'phone' => '09396988720',
            'password' => '123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123123',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertSessionHasErrors(['password']);
        $response->assertStatus(302);

        //////////////////////////////////////////////////

        $data = [
            'first_name' => 'dddd',
            'last_name' => 'karimi',
            'email' => 'dsfs@gmail.com',
            'phone' => '09396988720',
            'password' => 'ashkan12345',
        ];

        $response = $this->post('/api/auth/register', $data);

        $response->assertStatus(200);
    }
}

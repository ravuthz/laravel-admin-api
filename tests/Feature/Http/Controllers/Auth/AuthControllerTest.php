<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Services\AuthService;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private function createUser($username, $password = '123123')
    {
        return User::updateOrCreate([
            'name' => $username,
            'email' => $username . '@gm.com'
        ], [
            'password' => $password,
            'email_verified_at' => now()
        ]);
    }

    public function test_register()
    {
        $input = User::factory()->make()->toArray();
        $input['password'] = "123123";

        $res = $this->postJson(route('auth.register'), $input);

        $res->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'status',
                'message',
            ])
            ->assertJson([
                'message' => 'Successfully registered user',
            ]);
    }

    /**
     * Test user login API.
     *
     * @return void
     */
    public function test_login()
    {
        $user = $this->createUser('admin1');

        $input = (array) $user->only('email');
        $input['password'] = '123123';

        $res = $this->postJson(route('auth.login'), $input);

        $res->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token_type',
                    'access_token',
                    'expires_at',
                ],
                'status',
                'message'
            ])
            ->assertJson([
                'message' => 'Successfully logged in',
            ]);
    }

    /**
     * Test user logout API.
     *
     * @return void
     */
    public function test_logout()
    {
        $auth = AuthService::login(User::factory()->create());

        $this->withHeaders([
            'Authorization' => $auth['token_type'] . ' ' . $auth['access_token'],
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        $res = $this->getJson(route('auth.logout'));

        $res->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'Successfully logged out',
            ]);
    }

    private function requestToken($user, $password = null)
    {
        $client = Client::where('password_client', true)->first();

        return $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => $password ?? '123123',
            'scope' => ''
        ]);
    }

    public function test_request_token()
    {
        $user = $this->createUser('admin2');
        $res = $this->requestToken($user);

        $res->assertJsonStructure([
            'token_type', 'expires_in', 'access_token'
        ]);
    }

    public function test_refresh_token()
    {
        $client = Client::where('password_client', true)->first();

        $user = $this->createUser('admin2');
        $res1 = $this->requestToken($user);

        $res = $this->postJson('/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'refresh_token' => $res1->json('refresh_token'),
            'scope' => ''
        ]);

        $res->assertJsonStructure([
            'token_type', 'expires_in', 'access_token'
        ]);
    }

}

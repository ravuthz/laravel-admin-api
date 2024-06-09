<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected static bool $login = false;

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

    private function loginByName($name)
    {
        $user = User::saveByName($name);
        return $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => '123123'
        ]);
    }

    /**
     * Test user login API.
     *
     * @return void
     */
    public function test_login()
    {
        $res = $this->loginByName('admin1');
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
        $auth = $this->loginByName('admin2')->json('data');

//        $this->withHeaders([
//            'Accept' => 'application/json',
//            'Content-Type' => 'application/json',
//            'Authorization' => $auth['token_type'] . ' ' . $auth['access_token'],
//        ])->get();

        $res = $this
            ->withToken($auth['access_token'], $auth['token_type'])
            ->getJson(route('auth.logout'));

        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Successfully logged out',
            ]);
    }

    private function requestToken()
    {
        $user = User::factory()->create();
        $client = Client::where('password_client', true)->first();

        return $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => '123123',
            'scope' => ''
        ]);
    }

    public function test_request_token()
    {
        $res = $this->requestToken();

        $res->assertJsonStructure([
            'token_type', 'expires_in', 'access_token', 'refresh_token'
        ]);
    }

    public function test_refresh_token()
    {
        $token = $this->requestToken();

        $client = Client::where('password_client', true)->first();

        $res = $this->postJson('/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'refresh_token' => $token->json('refresh_token'),
            'scope' => ''
        ]);

        $res->assertStatus(200)->assertJsonStructure([
            'token_type', 'expires_in', 'access_token', 'refresh_token'
        ]);
    }
}

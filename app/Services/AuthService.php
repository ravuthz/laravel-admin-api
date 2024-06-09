<?php

namespace App\Services;

use Carbon\Carbon;

class AuthService
{
    public static function login($user, $rememberMe = false): array
    {
        $result = $user->createToken('Personal Access Token');

        $token = $result->token;

        if ($rememberMe) {
            $token->expires_at = now()->addWeeks(1);
        }

        $token->save();

        return [
            'token_type' => 'Bearer',
            'access_token' => $result->accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
        ];
    }

    public static function logout()
    {
//        request()->user()->tokens->map(fn ($token) => $token->revoke());
        request()->user()?->token()?->revoke();
        auth('web')->logout();
    }
}

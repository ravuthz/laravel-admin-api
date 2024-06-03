<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthService
{
    public static function login($user, $rememberMe = false)
    {
        $result = $user->createToken('Personal Access Token');

        $token = $result->token;

        if ($rememberMe) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return [
            'token_type' => 'Bearer',
            'access_token' => $result->accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
        ];
    }

    public static function logout(Request $request)
    {
        return $request->user()->token()->revoke();
    }
}

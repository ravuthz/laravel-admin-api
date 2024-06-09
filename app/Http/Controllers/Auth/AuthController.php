<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|min:3|max:255',
            'password' => 'required|string|min:6|max:50',
        ]);

        $user = User::create($input);
        return response()->success($user, 200, 'Successfully registered user');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|min:3|max:255',
            'password' => 'required|string|min:6|max:50',
        ]);

        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->error(null, 401, 'Unauthorized');
        }

        // remember_token
        $user = Auth::user(); // $request->user()
        $data = AuthService::login($user, $request->remember_me);
        return response()->success($data, 200, 'Successfully logged in');
    }

    public function logout()
    {
        AuthService::logout();
        return response()->success([], 200, 'Successfully logged out');
    }

    public function me(Request $request)
    {
        return response()->success($request->user(), 200, 'Authenticated user info.');
    }
}

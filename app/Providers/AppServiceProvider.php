<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enableImplicitGrant();
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Response::macro('success', function ($data, $status = 200, $message = 'Successfully') {
            return Response::json([
                'success'  => true,
                'message' => $message,
                'status' => $status,
                'data' => $data,
            ], $status);
        });

        Response::macro('error', function ($error, $status = 400, $message = 'Failed') {
            return Response::json([
                'success'  => false,
                'message' => $message,
                'status' => $status,
                'data' => []
            ], $status);
        });

    }
}

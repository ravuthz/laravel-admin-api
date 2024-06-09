<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    protected static bool $login = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (static::$login) {
//            $user = User::saveByName('admin');
            $user = User::factory()->create();
            $this->actingAs($user, 'api');
            $this->createPassportClients();
        }
    }

    public function createPassportClients()
    {
        $url = config('app.url');
        $clientRepository = new ClientRepository();
        $clientRepository->createPasswordGrantClient(null, 'Test Password Grant Client', $url);
        $clientRepository->createPersonalAccessClient(null, 'Test Personal Access Client', $url);
    }
}

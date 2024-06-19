<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'username' => 'TestUser',
            'email' => 'test@example.com',
        ], ['password' => '123123']);

        User::updateOrCreate([
            'username' => 'admin1',
            'email' => 'admin1@example.com',
        ], ['password' => '123123']);

        $this->call(UserRolePermissionSeeder::class);
    }
}

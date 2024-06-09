<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Crud\HasCrudControllerTest;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use HasCrudControllerTest;
    protected static bool $login = true;
    protected string $route = '/api/users';
    protected function setUp(): void
    {
        parent::setUp();
        $this->onceSetUp();
    }

    protected function inputBody($id = null): array
    {
        $time = now()->format('Y-m-d_H:m:s.u');
        $role = Role::saveByName('test-role');
        $permission = Permission::saveByName('test-permission');

        if (!empty($id)) {
            return [
                'name' => 'Test Name',
                'email' => 'test@gm' . $time . '.com',
                'password' => '123123',
                'roles' => [$role],
                'permissions' => [$permission]
            ];
        }

        return [
            'name' => 'Test Role Created',
            'email' => 'test@gm' . $time . '.com',
            'password' => '123123',
            'roles' => [$role],
            'permissions' => [$permission]
        ];
    }
}

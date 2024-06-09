<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Crud\HasCrudControllerTest;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use HasCrudControllerTest;
    protected static bool $login = true;
    protected string $route = '/api/roles';
    protected function setUp(): void
    {
        parent::setUp();
        $this->onceSetUp();
    }

    protected function inputBody($id = null): array
    {
//        $time = ceil(microtime(true) * 1000);
        $time = now()->format('Y-m-d_H:m:s.u');
        $test = Permission::saveByName('test-permission');

        if (!empty($id)) {
            return [
                'name' => 'Test Role Updated',
                'code' => 'test_role_' . $time,
                'permissions' => [$test]
            ];
        }

        return [
            'name' => 'Test Role Created',
            'code' => 'test_role_' . $time,
            'permissions' => [$test]
        ];
    }

}

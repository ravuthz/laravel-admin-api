<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Crud\HasCrudControllerTest;
use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class PermissionControllerTest extends TestCase
{
    use HasCrudControllerTest;
    protected static bool $login = true;
    protected string $route = '/api/permissions';
    protected function setUp(): void
    {
        parent::setUp();
        $this->onceSetUp();
    }

    protected function inputBody($id = null): array
    {
        $time = now()->format('Y-m-d_H:m:s.u');
        $test = Role::saveByName('test-role');

        if (!empty($id)) {
            return [
                'name' => 'Test Permission Updated',
                'code' => 'test_permission_' . $time,
                'roles' => [$test]
            ];
        }
        return [
            'name' => 'Test Permission Created',
            'code' => 'test_permission_' . $time,
            'roles' => [$test]
        ];
    }
}

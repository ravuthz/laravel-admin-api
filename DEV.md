# Development

## Using CrudService

```php
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Crud\CrudController;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;

class RoleController extends CrudController
{
    protected $model = Role::class;
    protected $storeRequest = RoleRequest::class;
    protected $updateRequest = RoleRequest::class;
    protected $resource = RoleResource::class;
    
    
}

```

## Add CrudServiceTesting

```php
use App\Crud\HasCrudControllerTest;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use HasCrudControllerTest;

    protected string $route = '/api/roles';

    protected function setUp(): void
    {
        parent::setUp();
        $this->onceSetUp();
    }

    protected function inputBody($id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => 'Test Role Updated',
                'code' => 'test_role_' . now()->format('Y-m-d H:m:s')
            ];
        }
        return [
            'name' => 'Test Role Created',
            'code' => 'test_role_' . now()->format('Y-m-d H:m:s')
        ];
    }

}
```

## Add API with Passport

```bash
php artisan install:api --passport

php artisan passport:keys

php artisan vendor:publish --tag=passport-config

php artisan make:controller Auth/AuthController --test

```

## Role Permission

```bash
php artisan make:model Role -mf
php artisan make:model Permission -mf

php artisan make:seeder UserRolePermissionSeeder
php artisan make:test UserRolePermissionTest --unit

php artisan make:request RoleRequest
php artisan make:resource RoleResource

php artisan make:request PermissionRequest
php artisan make:resource PermissionResource

php artisan make:controller Api/RoleController --test
php artisan make:controller Api/PermissionController --test

php artisan make:migration create_role_permissions_table
php artisan make:migration create_user_roles_table
php artisan make:migration create_user_permissions_table

php artisan make:request UserRequest
php artisan make:resource UserResource
php artisan make:controller Api/UserController --test

```

# Create clients
```bash
php artisan migrate:fresh --env=testing

php artisan passport:client --password --no-interaction --env=testing
php artisan passport:client --personal --no-interaction --env=testing

php artisan tinker --env=testing

```

# Setting
```bash
php artisan make:model SettingType -m
php artisan make:model Setting -m

php artisan make:request SettingTypeRequest
php artisan make:request SettingRequest

php artisan make:resource SettingTypeResource
php artisan make:resource SettingResource

php artisan make:seeder SettingSeeder

php artisan migrate:fresh --seed

php artisan make:controller Api/SettingTypeController --test
php artisan make:controller Api/SettingController --test

```

# CRUD CLI Generator
```bash

php artisan make:command CrudCommand
php artisan make:command CrudControllerCommand
php artisan make:command CrudControllerTestCommand

# Generate Only CRUD Resource Controller API
php artisan crud:controller Post

# Generate Only CRUD Resource Controller Test API
php artisan crud:controller-test Post

# Generate CRUD Resource Controller API with/without Test
php artisan crud:generate Post --test

# After generate CRUD Resource Controller API
# Add route resource apiResource('posts', 'PostController');
# Update the migration file
# Update the model add guarded
# Update the request authorize and rules
# Update resource to custom response ( optional )
# Update payload in test controller  ( optional )
# Update controller before and after save ( optional )

php artisan test --filter='Post'

```

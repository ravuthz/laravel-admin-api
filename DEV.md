# Development

## Using CrudService

```php
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Crud\CrudService;
use App\Crud\HasCrudService;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    use HasCrudService;

    public function __construct(protected CrudService $service)
    {
        $this->service->setModel(Role::class)
            ->setStoreRequest(StoreRoleRequest::class)
            ->setUpdateRequest(UpdateRoleRequest::class)
            ->setResource(RoleResource::class);
            // setCollection(RoleCollection::class);
    }

}

```

## Add CrudServiceTesting
```php
use App\Crud\HasCrudServiceTest;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use HasCrudServiceTest;

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

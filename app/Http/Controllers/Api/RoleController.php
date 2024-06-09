<?php

namespace App\Http\Controllers\Api;

use App\Crud\CrudController;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleController extends CrudController
{
    protected $model = Role::class;
    protected $storeRequest = RoleRequest::class;
    protected $updateRequest = RoleRequest::class;
    protected $resource = RoleResource::class;

    protected function afterSave($request, $model, $id = null)
    {
        $permissions = $request->get('permissions', null);

        if ($permissions) {
            $ids = array_map(fn($item) => $item['id'], $permissions);
            $model->permissions()->sync($ids);
        }
    }

}

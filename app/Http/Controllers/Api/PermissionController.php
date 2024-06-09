<?php

namespace App\Http\Controllers\Api;

use App\Crud\CrudController;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;

class PermissionController extends CrudController
{
    protected $model = Permission::class;
    protected $storeRequest = PermissionRequest::class;
    protected $updateRequest = PermissionRequest::class;
    protected $resource = PermissionResource::class;

    protected function afterSave($request, $model, $id = null)
    {
        $roles = $request->get('roles', null);

        if ($roles) {
            $ids = array_map(fn($item) => $item['id'], $roles);
            $model->roles()->sync($ids);
        }
    }
}

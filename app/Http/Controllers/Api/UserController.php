<?php

namespace App\Http\Controllers\Api;

use App\Crud\CrudController;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends CrudController
{
    protected $model = User::class;
    protected $storeRequest = UserRequest::class;
    protected $updateRequest = UserRequest::class;
    protected $resource = UserResource::class;

    protected function afterSave($request, $model, $id = null)
    {
        $roles = $request->get('roles', null);
        $permissions = $request->get('permissions', null);

        if ($roles) {
            $ids = array_map(fn($item) => $item['id'], $roles);
            $model->roles()->sync($ids);
        }

        if ($permissions) {
            $ids = array_map(fn($item) => $item['id'], $permissions);
            $model->permissions()->sync($ids);
        }
    }
}

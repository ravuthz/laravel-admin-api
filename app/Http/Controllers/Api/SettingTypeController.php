<?php

namespace App\Http\Controllers\Api;

use App\Crud\CrudController;
use App\Http\Requests\SettingTypeRequest;
use App\Http\Resources\SettingTypeResource;
use App\Models\SettingType;

class SettingTypeController extends CrudController
{
    protected $model = SettingType::class;
    protected $storeRequest = SettingTypeRequest::class;
    protected $updateRequest = SettingTypeRequest::class;
    protected $resource = SettingTypeResource::class;
}

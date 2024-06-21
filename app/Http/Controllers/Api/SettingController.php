<?php

namespace App\Http\Controllers\Api;

use App\Crud\CrudController;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;

class SettingController extends CrudController
{
    protected $model = Setting::class;
    protected $storeRequest = SettingRequest::class;
    protected $updateRequest = SettingRequest::class;
    protected $resource = SettingResource::class;
}

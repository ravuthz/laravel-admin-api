<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Crud\HasCrudControllerTest;
use Tests\TestCase;

class SettingTypeControllerTest extends TestCase
{
    use HasCrudControllerTest;

    protected static bool $login = true;
    protected string $route = '/api/setting-types';

    protected function setUp(): void
    {
        parent::setUp();
        $this->onceSetUp();
    }

    protected function inputBody($id = null): array
    {
        $time = now()->format('Y-m-d_H:m:s.u');
        $action = empty($id) ? 'Created' : 'Updated';

        return [
            'code' => "setting-code-{$action}-{$time}",
            'name' => "Setting Type $action $time",
            'description' => "setting_type_{$action} {$time}",
        ];
    }
}

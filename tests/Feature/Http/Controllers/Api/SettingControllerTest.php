<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Crud\HasCrudControllerTest;
use Tests\TestCase;

class SettingControllerTest extends TestCase
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
            'type_id' => 1,
            'code' => "setting-code-{$action}-{$time}",
            'name' => "Setting $action $time",
            'value' => "setting_value_{$action} {$time}",
            'description' => "setting_{$action} {$time}",
            'options' => [
                'json' => [
                    'support' => true
                ]
            ],
        ];
    }
}

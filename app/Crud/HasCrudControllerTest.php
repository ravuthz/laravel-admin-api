<?php

namespace App\Crud;

//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Laravel\Passport\ClientRepository;

trait HasCrudControllerTest
{
//    use RefreshDatabase;

    protected static bool $running = false;
    protected int $idOk = 1;
    protected int $idFail = 9999;
    protected function onceSetUp(): void
    {
//        $this->withoutMiddleware();

        if (!static::$running) {
            $this->artisan('migrate:fresh');

            if (config('app.debug')) {
                dump(config('app.env') . ' ' . config('database.default'));
            }

//            $url = config('app.url');
//            $clientRepository = new ClientRepository();
//            $clientRepository->createPasswordGrantClient(null, 'Test Password Grant Client', $url);
//            $clientRepository->createPersonalAccessClient(null, 'Test Personal Access Client', $url);

            static::$running = true;
        }
    }

    public function test_index(): void
    {
        $res = $this->getJson($this->route . '/');
        $res->assertStatus(200);
    }

    public function test_store(): void
    {
        $res = $this->postJson($this->route . '/', $this->inputBody());
        $res->assertStatus(200);
    }

    public function test_show(): void
    {
        $res = $this->getJson($this->route . '/' . $this->idOk);
        $res->assertStatus(200);
    }

    public function test_show_not_found(): void
    {
        $res = $this->getJson($this->route . '/' . $this->idFail);
        $res->assertStatus(404);
    }

    public function test_update(): void
    {
        $res = $this->patchJson($this->route . '/' . $this->idOk, $this->inputBody($this->idOk));
        $res->assertStatus(200);
    }

    public function test_update_not_found(): void
    {
        $res = $this->patchJson($this->route . '/' . $this->idFail, $this->inputBody($this->idOk));
        $res->assertStatus(404);
    }

    public function test_destroy(): void
    {
        $res = $this->deleteJson($this->route . '/' . $this->idOk);
        $res->assertStatus(200);
    }

    public function test_destroy_not_found(): void
    {
        $res = $this->deleteJson($this->route . '/' . $this->idFail);
        $res->assertStatus(404);
    }
}

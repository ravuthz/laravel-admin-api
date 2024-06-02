<?php

namespace App\Crud;

use Exception;
use Illuminate\Http\Request;

trait HasCrudService
{
    protected CrudService $service;

    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index(Request $request)
    {
        return $this->service->list($request);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(Request $request)
    {
        return $this->service->save($request);
    }

    /**
     * Display the specified resource.
     * @throws Exception
     */
    public function show(string $id)
    {
        return $this->service->show($id);
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(Request $request, string $id)
    {
        return $this->service->save($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     * @throws Exception
     */
    public function destroy(string $id)
    {
        return $this->service->delete($id);
    }
}

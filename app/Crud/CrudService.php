<?php

namespace App\Crud;

use Exception;
use Illuminate\Database\Eloquent\Model;

class CrudService
{
    private Model|null $model;
    private string|null $resource;
    private string|null $collection;
    private string|null $storeRequest;
    private string|null $updateRequest;

    public function __construct(
        $model = null,
        $storeRequest = null,
        $updateRequest = null,
        $resource = null,
        $collection = null)
    {
        $this->model = $model ? app($model) : null;
        $this->resource = $resource;
        $this->collection = $collection ?? $resource;
        $this->storeRequest = $storeRequest;
        $this->updateRequest = $updateRequest;
    }

    public function setModel($model): static
    {
        $this->model = app($model);
        return $this;
    }

    /**
     * @return Model
     * @throws Exception
     */
    public function getModel(): Model
    {
        if (!$this->model) {
            throw new Exception("The model is required", 400);
        }
        return $this->model;
    }

    public function setStoreRequest($request): static
    {
        $this->storeRequest = $request;
        return $this;
    }

    public function setUpdateRequest($request): static
    {
        $this->updateRequest = $request;
        return $this;
    }

    public function setResource($resource): static
    {
        $this->resource = $resource;
        return $this;
    }

    public function setCollection($collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    public function responseList($data, $status = null, $message = null)
    {
        if ($this->collection) {
            $data = $this->collection::make($data);
        }
        if ($this->resource) {
            $data = $this->resource::collection($data);
        }
        return $this->responseJson($data, $status, $message);
    }

    public function responseItem($data, $status = null, $message = null)
    {
        if ($this->resource) {
            $data = $this->resource::make($data);
        }
        return $this->responseJson($data, $status, $message);
    }

    public function responseJson($data, $status = null, $message = null)
    {
        $status = $status ?? 200;
        return response()->json([
            'data' => $data ?? [],
            'status' => $status,
            'message' => $message ?? 'Successfully'
        ], $status);
    }

    /**
     * @throws Exception
     */
    public function findOne(string $id)
    {
        if (method_exists($this->getModel(), 'findOne')) {
            return $this->getModel()->findOne($id);
        }
        return $this->getModel()->findOrFail($id);
    }

    /**
     * @throws Exception
     */
    public function findAll($request)
    {
        if (method_exists($this->getModel(), 'findAll')) {
            return $this->getModel()->findAll($request);
        }
        return $this->getModel()->paginate($request->get('size', 10));
    }

    /**
     * @throws Exception
     */
    public function list($request)
    {
        $data = $this->findAll($request);
        return $this->responseList($data);
    }

    /**
     * @throws Exception
     */
    public function show(string $id)
    {
        $data = $this->findOne($id);
        return $this->responseItem($data);
    }

    /**
     * @throws Exception
     */
    public function delete(string $id)
    {
        $this->findOne($id)->delete();
        return $this->responseItem(null);
    }

    /**
     * @throws Exception
     */
    public function save($request, string $id = null, callable $beforeSaveFn = null)
    {
        if ($id == null) {
            $model = $this->getModel();
            $input = $this->storeRequest ? app($this->storeRequest)->validated() : $request->all();
        } else {
            $model = $this->findOne($id);
            $input = $this->updateRequest ? app($this->updateRequest)->validated() : $request->all();
        }

        if (is_callable($beforeSaveFn)) {
            $beforeSaveFn($model, $input);
        }

        $model->fill($input);
        $model->save();

        return $this->responseItem($model, 200, 'Saved');
    }
}

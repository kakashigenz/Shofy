<?php

namespace App\Repositories;

use App\Models\VariationOption;

class VariationOptionRepository implements IVariationOptionRepository
{
    protected $model;

    public function __construct(VariationOption $model)
    {
        $this->model = $model;
    }
    function create($data)
    {
        return $this->model->create($data);
    }

    function delete($id)
    {
        return $this->model::query()->find($id)->delete();
    }

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::where($field,$op,$value);
    }
}

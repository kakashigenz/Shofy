<?php

namespace App\Repositories;

use App\Models\AttributeProduct;

class AttributeProductRepository implements IAttributeProductRepository{

    protected $model;

    public function __construct(AttributeProduct $model) {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model::query()->create($data);
    }

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::query()->where($field,$op,$value);
    }
}
<?php

namespace App\Repositories;

use App\Models\ProductItem;

class ProductItemRepository implements IProductItemRepository
{
    protected $model;
    public function __construct(ProductItem $model)
    {
        $this->model = $model;
    }
    public function create($data)
    {
        return $this->model::create($data);
    }

    public function update($id, $data)
    {
        return $this->model::query()->where('id', $id)->update($data);
    }
}

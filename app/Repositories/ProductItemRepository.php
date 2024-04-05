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

    public function show(string $id){
        return $this->model::with('variationOption')->find($id);
    }

    public function update($id, $data)
    {
        return $this->model::query()->where('id', $id)->update($data);
    }

    public function delete(string $id)
    {
        return $this->model::where('id',$id)->delete();
    }

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::query()->where($field,$op,$value);
    }
}

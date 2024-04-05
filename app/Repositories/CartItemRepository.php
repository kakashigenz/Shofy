<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartItemRepository implements ICartItemRepository{
    protected $model;

    public function __construct(CartItem $model) {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model::query()->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->model::query()->where('id',$id)->update($data);
    }

    public function delete(string $id)
    {
        return $this->model::query()->where('id',$id)->delete();
    }

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::query()->where($field,$op,$value);
    }
    
}
<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements IOrderRepository{
    protected $model;

    public function __construct(Order $model) {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model::query()->create($data);
    }
}
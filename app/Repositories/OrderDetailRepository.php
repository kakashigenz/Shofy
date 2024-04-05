<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository implements IOrderDetailRepository{
    protected $model;

    public function __construct(OrderDetail $model) {
        $this->model = $model;
    }
    public function create(array $data)
    {
        return $this->model::query()->create($data);
    }
}
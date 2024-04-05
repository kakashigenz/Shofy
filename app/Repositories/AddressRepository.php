<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository implements IAddressRepository{
    protected $model;

    public function __construct(Address $model) {
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

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::query()->where($field,$op,$value);
    }
}
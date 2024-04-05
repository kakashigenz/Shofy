<?php

namespace App\Repositories;

interface IAddressRepository{
    public function create(array $data);
    public function update(string $id,array $data);
    public function where(string $field,string $value,string $op = '=');
}
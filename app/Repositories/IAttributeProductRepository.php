<?php

namespace App\Repositories;

interface IAttributeProductRepository {
    public function create(array $data);
    public function where(string $field,string $value,string $op = '=');
}
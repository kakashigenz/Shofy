<?php

namespace App\Repositories;

interface ICartItemRepository {
    public function create(array $data);
    public function update(string $id,array $data);
    public function delete(string $id);
    public function where(string $field,string $value,string $op='=');
}
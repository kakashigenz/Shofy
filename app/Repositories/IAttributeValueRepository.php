<?php

namespace App\Repositories;


interface IAttributeValueRepository{
    public function create(array $data);
    public function update(string $id,array $data);
    public function delete(string $id);
}
<?php
namespace App\Repositories;

interface IProductImageRepository{
    public function create(array $data);
    public function delete(string $id);
    public function update(string $id,array $data);
    public function where(string $field,string $value,string $op = '=');
}
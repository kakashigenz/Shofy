<?php
namespace App\Repositories;


interface IAttributeRepository{
    public function index();
    public function show(int $id);
    public function create(array $data);
    public function update(string $id,array $data);
    public function delete(string $id);
    public function getList(string $search,int $page,int $length);
    public function getByCategory(string $categoryId);
}
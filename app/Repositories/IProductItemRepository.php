<?php

namespace App\Repositories;

interface IProductItemRepository
{
    function create($data);
    function update($id, $data);
    function show(string $id);
    function delete(string $id);
    function where(string $field,string $value,string $op = '=');
}

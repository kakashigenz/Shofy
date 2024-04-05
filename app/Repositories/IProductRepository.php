<?php

namespace App\Repositories;

interface IProductRepository
{
    function all($search);
    function create($data);
    function getList($search, $start, $length);
    function show($id);
    function update($id, $data);
    function destroy($id);
    function where(string $field,string $value,string $op = '=');
}

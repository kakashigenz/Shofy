<?php

namespace App\Repositories;

interface IVariationOptionRepository
{
    function create($data);
    function where(string $field,string $value,string $op = '=');
    function delete($id);
}

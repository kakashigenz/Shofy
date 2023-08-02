<?php

namespace App\Repositories;

interface IProductItemRepository
{
    function create($data);
    function update($id, $data);
}

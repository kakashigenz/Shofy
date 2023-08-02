<?php

namespace App\Repositories;

interface IProductConfigurationRepo
{
    function create($data);
    function update($product_item_id, $variation_option_id, $data);
}

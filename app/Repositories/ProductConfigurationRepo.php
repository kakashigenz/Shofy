<?php

namespace App\Repositories;

use App\Models\ProductConfiguration;

class ProductConfigurationRepo implements IProductConfigurationRepo
{
    protected $model;
    public function __construct(ProductConfiguration $model)
    {
        $this->model = $model;
    }
    function create($data)
    {
        return $this->model::create($data);
    }

    public function update($product_item_id, $variation_option_id, $data)
    {
        return $this->model::query()->where('product_item_id', $product_item_id)
            ->where('variation_option_id', $variation_option_id)
            ->update($data);
    }
}

<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements IproductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all($search)
    {
        return $this->model::join('product_items', 'products.id', '=', 'product_items.product_id')
            ->where('products.name', 'like', "%$search%")->get();
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function getList($search, $start, $length)
    {

        return $this->model::with('category')->where('name', 'like', "%$search%")
            ->offset($start)->limit($length)->get();
    }

    public function show($id)
    {
        return $this->model::query()->find($id)->with('productItem.variationOption')->first();
    }

    public function update($id, $data)
    {
        return $this->model::query()->where('id', $id)->update($data);
    }

    public function destroy($id){
        return $this->model::with('productItem')->delete();
    }
}

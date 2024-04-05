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
        return $this->model::with('productItem')
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
        return $this->model::query()->with(['productItem.variationOption','productImage','attributeValues'])->find($id);
    }

    public function update($id, $data)
    {
        return $this->model::query()->where('id', $id)->update($data);
    }

    public function destroy($id){
        return $this->model::with('productItem')->delete();
    }

    public function where(string $field,string $value,string $op = '='){
        return $this->model::where($field,$op,$value);
    }
}

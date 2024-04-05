<?php
namespace App\Repositories;

use App\Models\ProductImage;

class ProductImageRepository implements IProductImageRepository{

    protected $model;

    public function __construct(ProductImage $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->model::where('id',$id)->update($data);
    }

    public function delete(string $id){
        return $this->model::where('id',$id)->delete();
    }

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::where($field,$op,$value);
    }
}
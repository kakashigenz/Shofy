<?php
namespace App\Repositories;

use App\Models\AttributeValue;

class AttributeValueRepository implements IAttributeValueRepository{
    protected $model;
    public function __construct(AttributeValue $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
       return $this->model::create($data);
    }

    public function update(string $id,array $data)
    {
        return $this->model::where('id',$id)->update($data);
    }

    public function delete(string $id){
        return $this->model::where('id',$id)->delete();
    }
}
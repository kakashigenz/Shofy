<?php
namespace App\Repositories;

use App\Models\Attribute;

class AttributeRepository implements IAttributeRepository{
    protected $model;

    public function __construct(Attribute $model) {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model::all();
    }

    public function show(int $id)
    {
        return $this->model::with('values')->where('id',$id)->get();
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->model::where('id',$id)->update($data);
    }

    public function delete(string $id)
    {
        $attribute = $this->model::find($id);
        $attribute->values()->delete();
        return $attribute->delete();
    }

    public function getList(string|null $search, int $page, int $length)
    {
        $start = ($page - 1 ) * $length;
        return $this->model::with('category')->where('name','like',"%$search%")->skip($start)->take($length)->get();
    }

    public function getByCategory(string $categoryId){
        return $this->model::with('values')->where('category_id',$categoryId)->get();
    }
}
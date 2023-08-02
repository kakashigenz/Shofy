<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository implements ICategoryRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = Category::class;
    }

    public function all($type, $search)
    {
        return $this->model::with('variation')->where('type', $type)
            ->where('name', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
            ->with('parentCategory', 'variation')
            ->get(['id', 'name', 'slug', 'parent_category_id', 'created_at']);
    }

    public function find($id)
    {
        return $this->model::with('parentCategory', 'variation')->find($id);
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function update($data, $id)
    {
        return $this->model::where('id', $id)->update($data);
    }

    public function destroy($id)
    {
        return $this->model::where('id', $id)->delete();
    }

    public function getList($type, $search, $start, $length)
    {
        return $this->model::where('type', $type)
            ->where('name', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
            ->skip($start)
            ->take($length)
            ->with('parentCategory', 'variation.option')
            ->get(['id', 'name', 'slug', 'parent_category_id', 'created_at']);
    }
}

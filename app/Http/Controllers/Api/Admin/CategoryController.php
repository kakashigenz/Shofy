<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ICategoryRepository;
use App\Repositories\IAttributeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $repository;
    protected $attribute_repo;

    public function __construct(ICategoryRepository $repository, IAttributeRepository $attribute_repo)
    //đặt tên là varriation nhưng là attribute
    {
        $this->repository = $repository;
        $this->attribute_repo = $attribute_repo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');
        $start = $request->input('start');
        $length = $request->input('length');
        $data = $this->repository->getList($type, $search, $start, $length);
        $total = $this->repository->all($type, $search)->count();
        $res = [
            'data' => $data,
            'total' => $total
        ];
        return $res;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $data = $request->validate([
                'name' => 'required',
                'parent_category_id' => 'nullable',
                'type' => 'numeric',
            ]);

            $data['slug'] = $this->getSlug($data['name']);

            DB::beginTransaction();
            $category = $this->repository->create($data);
            // foreach ($data['variation'] as $value) {
            //     $this->attribute_repo->create([
            //         'category_id' => data_get($category, 'id'),
            //         'name' => $value
            //     ]);
            // }

            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->repository->find($id);
        return $category ?? response()->json(['message' => 'Not found'], 400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'parent_category_id' => 'nullable'
            ]);

            $data['slug'] = $this->getSlug($data['name']);
            $this->repository->update($data, $id);

            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = $this->repository->find($id);
            DB::beginTransaction();
            $attributes = $category->attributes;
            foreach ($attributes as $attribute) {
                $attribute->values()->delete();
                $attribute->delete();
            }
            $category->delete();
            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['code' => $th->getCode(), 'message' => $th->getMessage()], 400);
        }
    }

    private function getSlug($product_name){
        $slug = Str::slug($product_name);
        $count_slug = $this->repository->where('slug',$slug)->withTrashed()->count();
        if ($count_slug > 0){
            $slug .= Str::random(7);
        }
        return $slug;
    }
}

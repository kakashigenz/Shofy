<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ICategoryRepository;
use App\Repositories\IVariationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $repository;
    protected $variation_repo;

    public function __construct(ICategoryRepository $repository, IVariationRepository $variation_repo)
    {
        $this->repository = $repository;
        $this->variation_repo = $variation_repo;
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
                'slug' => 'required',
                'parent_category_id' => 'nullable',
                'type' => 'numeric',
                'variation' => 'array'
            ]);

            DB::beginTransaction();
            $category = $this->repository->create($data);
            foreach ($data['variation'] as $value) {
                $this->variation_repo->create([
                    'category_id' => data_get($category, 'id'),
                    'name' => $value
                ]);
            }

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
                'slug' => 'required|unique:categories,slug,' . $id,
                'parent_category_id' => 'nullable'
            ]);

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
            $this->repository->destroy($id);
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['code' => $th->getCode(), 'message' => $th->getMessage()], 400);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\IAttributeRepository;
use App\Repositories\IAttributeValueRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    protected $repo,$value_repo;

    public function __construct(IAttributeRepository $repo,IAttributeValueRepository $value_repo)
    {
        $this->repo = $repo;
        $this->value_repo = $value_repo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->validate([
                'search' => 'string|nullable',
                'page' => 'numeric|required',
                'length' => 'numeric|required'
            ]);
            $total = $this->repo->index()->count();
            $values = $this->repo->getList($data['search'],$data['page'],$data['length']);
            $res = [
                'data' => $values,
                'total' => $total,
            ];
            return $res;
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'category_id' => 'required',
                'values' => 'nullable|array'
            ]);
            DB::beginTransaction();
            $attribute = $this->repo->create($data);
            //TODO: kiểm tra nếu có mảng các giá trị thì cần viết logic thêm mảng các giá trị vào attribute_values
            if (data_get($data,'values')){
                foreach ($data['values'] as $value) {
                    $this->value_repo->create([ 'value'=>$value,'attribute_id'=> data_get($attribute,'id') ]);
                }
            }
            DB::commit();
            return response()->json(['message'=>'success'],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>$th->getMessage()],400);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->repo->show($id)->first();
        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'category_id' => 'numeric'
            ]);

            $this->repo->update($id,$data);
            return response()->json(['message'=>'success']);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->repo->delete($id);
            return response()->json(['message'=>'success'],200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function getByCategory(string $categoryId){
        try {
            return $this->repo->getByCategory($categoryId);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
}

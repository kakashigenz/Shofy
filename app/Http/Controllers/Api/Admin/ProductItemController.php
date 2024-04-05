<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\IProductItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductItemController extends Controller
{
    protected $product_item_repo;

    public function __construct(IProductItemRepository $product_item_repo) {
        $this->product_item_repo = $product_item_repo;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $item = $this->product_item_repo->show($id);
            $item->variationOption()->detach();
            $item->delete();
            DB::commit();
            return response()->json(['message'=>'success'],200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
}

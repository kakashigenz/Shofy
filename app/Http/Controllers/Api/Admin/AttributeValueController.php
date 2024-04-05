<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\IAttributeValueRepository;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $repo;

    public function __construct(IAttributeValueRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'value' => 'string|required',
                'attribute_id' => 'required|numeric'
            ]);

            $this->repo->create($data);

            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],400);
        }
        
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
        try {
            $data = $request->validate([
                'value' => 'string|required',
                'attribute_id' => 'required|numeric'
            ]);

            $this->repo->update($id,$data);

            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->repo->delete($id);
            return response()->json(['message' => 'success']);

        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],400);
        }
    }
}

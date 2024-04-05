<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\IAddressRepository;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $address_repo;

    public function __construct(IAddressRepository $address_repo) {
        $this->address_repo = $address_repo;
    }

    public function store(Request $request){
        try {
            $data = $request->validate([
                'name' => 'required',
                'phone' => 'regex:/^\d{10}$/',
                'city_code' => 'integer',
                'district_code' => 'integer',
                'ward_code' => 'integer',
                'address' => 'required',
                'is_default' => 'nullable'
            ]);
            
            $data['user_id'] = auth('sanctum')->user()->id;

            if (isset($data['is_default'])){
                $data['is_default'] = true;
            }
            $this->address_repo->create($data);

            return response()->json(['message'=>'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()],400);
        }
    }

    public function update(Request $request,string $id){
        try {
            $data = $request->validate([
                'name' => 'required',
                'phone' => 'regex:/^\d{10}$/',
                'city_code' => 'integer',
                'district_code' => 'integer',
                'ward_code' => 'integer',
                'address' => 'required',
                'is_default' => 'nullable'
            ]);

            $data['user_id'] = auth('sanctum')->user()->id;

            if (isset($data['is_default'])){
                $data['is_default'] = true;
            }
            $this->address_repo->update($id,$data);
            return response()->json(['message'=>'success']);
        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],400);
        }
    }

    public function destroy(string $id){
        try {
            $this->address_repo->where('id',$id)->delete();
            return response()->json(['message'=>'success']);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
}

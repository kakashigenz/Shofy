<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\IAddressRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function App\Helpers\callApiGoship;

class GoshipController extends Controller
{
    protected $address_repo;

    public function __construct(IAddressRepository $address_repo) {
        $this->$address_repo =$address_repo;
    }

    public function getCities(){
        try {
            $data = cache()->store('redis')->remember('cities',Carbon::now()->addMonths(1),function (){
                $data = callApiGoship('http://sandbox.goship.io/api/v2/cities','GET');
                $res = null;
                if (data_get($data,'status') == 'success'){
                    $res = data_get($data,'data');
                }
                return $res;
            });
            return response()->json(['message'=>'success','data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
        
    }

    public function getDistrictsByCity(string $id){
        try {
            $data = cache()->store('redis')->remember("district:$id",Carbon::now()->addMonths(1),function () use($id){
                $data = callApiGoship(sprintf('http://sandbox.goship.io/api/v2/cities/%s/districts',$id),'GET');
                $res = null;
                if (data_get($data,'status') == 'success'){
                    $res = data_get($data,'data');
                }
                return $res;
            });
            return response()->json(['message'=>'success','data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()]);
        }
    }

    public function getWardsByDistrict(string $id){
        try {
            $data = cache()->store('redis')->remember("ward:$id",Carbon::now()->addMonths(1),function ()use($id){
                $data = callApiGoship(sprintf('http://sandbox.goship.io/api/v2/districts/%s/wards',$id),'GET');
                $res = null;
                if (data_get($data,'status') == 'success'){
                    $res = data_get($data,'data');
                }
                return $res;
            });
            return response()->json(['message'=>'success','data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()]);
        }
    }

    public function makeRate(Request $request){
        try {

            $data = $request->validate([
                'address_id' => 'required',
                'items' => 'array'
            ]);

            $address = $this->address_repo->where('id',$data['id'])->first();


            foreach ($data['items'] as $item) {
                $info = [
                    'shipment' => [
                        'address_from' => [
                            'district' => $address->district_code,
                            'city' =>  $address->city_code
                        ],
                        'address_to' => [
                            'district' => '100100',
                            'city' =>  '100000'
                        ],
                        'parcel' => [
                            'cod' => data_get($item,'product_item.price'),
                            'width' => data_get($item,'product_item.product.width'),
                            'height' => data_get($item,'product_item.product.height'),
                            'length' => data_get($item,'product_item.product.length'),
                            'weight' => data_get($item,'product_item.product.weight'),
                        ]
                    ]
                ];
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

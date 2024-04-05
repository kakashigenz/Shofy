<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\IAddressRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use function App\Helpers\callApiGoship;

class AddressController extends Controller
{
    protected $address_repo;

    public function __construct(IAddressRepository $address_repo) {
        $this->address_repo = $address_repo;
    }

    public function index(){
        $cities = cache()->store('redis')->remember('cities',Carbon::now()->addMonths(1),function (){
            $data = callApiGoship('http://sandbox.goship.io/api/v2/cities','GET');
            $res = null;
            if (data_get($data,'status') == 'success'){
                $res = data_get($data,'data');
            }
            return $res;
        });

        $user = auth('sanctum')->user();
        $addresses = $this->address_repo->where('user_id',$user->id)->get();
        foreach ($addresses as $address) {
            $districts =  cache()->store('redis')->remember("district:$address->city_code",Carbon::now()->addMonth(1),function () use($address){
                $data = callApiGoship(sprintf("http://sandbox.goship.io/api/v2/cities/%s/districts",$address->city_code),'GET');
                $res = null;
                if (data_get($data,'status') == 'success'){
                    $res = data_get($data,'data');
                }
                return $res;
            });

            $wards =  cache()->store('redis')->remember("ward:$address->district_code",Carbon::now()->addMonth(1),function () use($address){
                $data = callApiGoship(sprintf("http://sandbox.goship.io/api/v2/districts/%s/wards",$address->district_code),'GET');
                $res = null;
                if (data_get($data,'status') == 'success'){
                    $res = data_get($data,'data');
                }
                return $res;
            });

            $city = Arr::first($cities,function ($item) use($address){
                return data_get($item,'id') == $address->city_code;
            });

            $district = Arr::first($districts,function ($item) use($address){
                return data_get($item,'id') == $address->district_code;
            });

            $ward = Arr::first($wards,function ($item) use($address){
                return data_get($item,'id') == $address->ward_code;
            });

            $address['full_address'] = sprintf('%s,%s,%s,%s',$address->address,$ward['name'],$district['name'],$city['name']);
        }
        return view('address.index',['title'=>'Danh sách địa chỉ','addresses' => $addresses]);
    }

    public function store(){
        return view('address.store',['title'=>'Thêm địa chỉ']);
    }

    public function update(string $id){
        $address = $this->address_repo->where('id',$id)->firstOrFail();
        return view('address.update',['title'=> 'Cập nhật địa chỉ','address'=>$address]);
    }
}

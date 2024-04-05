<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\IAddressRepository;
use App\Repositories\ICartItemRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use function App\Helpers\callApiGoship;

class OrderController extends Controller
{
    protected $cart_item_repo;
    protected $address_repo;

    public function __construct(ICartItemRepository $cart_item_repo, IAddressRepository $address_repo)
    {
        $this->cart_item_repo = $cart_item_repo;
        $this->address_repo = $address_repo;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'l' => 'required'
        ]);
        $list_item = base64_decode($data['l']);
        $user = auth('sanctum')->user();
        $cart_items = $this->cart_item_repo->where('user_id', $user->id)->whereIn('id', explode(',', $list_item))
            ->with('productItem.product', 'productItem.variationOption')->get();

        $address = $this->address_repo->where('user_id', $user->id)->where('is_default', true)->first();

        $cities = cache()->store('redis')->remember('cities', Carbon::now()->addMonth(), function () {
            $res = callApiGoship('http://sandbox.goship.io/api/v2/cities', 'GET');
            $data = null;
            if ($res['status'] == 'success') {
                $data = $res['data'];
            }
            return $data;
        });
        $districts = cache()->store('redis')->remember(
            sprintf('district:%s', $address['city_code']),
            Carbon::now()->addMonth(),
            function () use ($address) {
                $res = callApiGoship(sprintf('http://sandbox.goship.io/api/v2/cities/%s/districts', $address['city_code']), 'GET');
                $data = null;
                if ($res['status'] == 'success') {
                    $data = $res['data'];
                }
                return $data;
            }
        );
        $wards = cache()->store('redis')->remember(
            sprintf('ward:%s', $address['district_code']),
            Carbon::now()->addMonth(),
            function () use ($address) {
                $res = callApiGoship(sprintf('http://sandbox.goship.io/api/v2/districts/%s/wards', $address['district_code']), 'GET');
                $data = null;
                if ($res['status'] == 'success') {
                    $data = $res['data'];
                }
                return $data;
            }
        );

        $city = Arr::first($cities, function ($item) use ($address) {
            return $item['id'] = $address['city_code'];
        });

        $district = Arr::first($districts, function ($item) use ($address) {
            return $item['id'] = $address['district_code'];
        });

        $ward = Arr::first($wards, function ($item) use ($address) {
            return $item['id'] = $address['ward_code'];
        });

        $res_address = [
            'id' => $address['id'],
            'name' => $address['name'],
            'phone' => $address['phone'],
            'full_address' => sprintf('%s,%s,%s,%s', $address['address'], $ward['name'], $district['name'], $city['name'])
        ];

        foreach ($cart_items as $item) {
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
                        'cod' => (int)data_get($item, 'productItem.price') * (int)data_get($item, 'quantity'),
                        'width' => data_get($item, 'productItem.product.width'),
                        'height' => data_get($item, 'productItem.product.height'),
                        'length' => data_get($item, 'productItem.product.length'),
                        'weight' => data_get($item, 'productItem.product.weight'),
                    ]
                ]
            ];
            $res_rate = callApiGoship('http://sandbox.goship.io/api/v2/rates', 'POST', $info);
            if ($res_rate['status'] == 'success') {
                $item->rate = [
                    'selected' => [
                        'id' => data_get($res_rate, 'data.0.id'),
                        'carrier_name' => data_get($res_rate, 'data.0.carrier_name'),
                        'total_fee' => data_get($res_rate, 'data.0.total_fee'),
                        'expected' => data_get($res_rate, 'data.0.expected'),
                    ],
                    'data' => data_get($res_rate, 'data')
                ];
            } else {
                $item->rate = [
                    'selected' => null,
                    'data' => []
                ];
            }
        }

        return view('order.store', [
            'title' => 'Đặt hàng',
            'items' => $cart_items,
            'address' => $res_address
        ]);
    }
}

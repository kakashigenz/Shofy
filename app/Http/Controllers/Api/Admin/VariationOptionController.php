<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\IProductConfigurationRepo;
use App\Repositories\IProductItemRepository;
use App\Repositories\IProductRepository;
use App\Repositories\IVariationOptionRepository;
use App\Repositories\IVariationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\generateSKU;

class VariationOptionController extends Controller
{
    protected $option_repo;
    protected $product_item_repo;
    protected $variation_repo;
    protected $product_repo;
    protected $product_config_repo;

    public function __construct(IVariationOptionRepository $option_repo,
        IProductItemRepository $product_item_repo,
        IVariationRepository $variation_repo,
        IProductRepository $product_repo,
        IProductConfigurationRepo $product_config_repo
    )
    {
        $this->option_repo = $option_repo;
        $this->product_item_repo = $product_item_repo;
        $this->variation_repo = $variation_repo;
        $this->product_repo = $product_repo;
        $this->product_config_repo = $product_config_repo;
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
        try {
            $data = $request->validate([
                'product_id' => 'integer',
                'variation_id' => 'integer',
                'product_items' => 'array',
                'variation_options' => 'array'
            ]);

            $variation_option_ids = [];
            $other_options = [];

            $product = $this->product_repo->where('id',data_get($data,'product_id'))->with('variations')->first();

            DB::beginTransaction();
            foreach (data_get($product,'variations') as $value) {
                if (data_get($value,'id') != data_get($data,'variation_id')){
                    $other_options = array_merge($other_options,$value->option->toArray());
                }
            }

            foreach ($other_options as $value) {
                $key = data_get($value,'variation_id'). '-' . data_get($value,'value');
                $variation_option_ids[$key] = data_get($value,'id');
            }

            foreach (data_get($data,'variation_options') as $value) {
                #viết logic kiểm tra option đã tồn tại chưa
                $option = $this->option_repo->where('variation_id',data_get($data,'variation_id'))
                ->where('value',$value)->first();

                if ($option){
                    throw new \Exception("Giá trị biến thể bị trùng",2406);
                }

                $key = data_get($data,'variation_id'). '-' . $value;
                $new_option = $this->option_repo->create([
                    'variation_id' => data_get($data,'variation_id'),
                    'value' => $value
                ]);
                $variation_option_ids[$key] = data_get($new_option,'id');
            }

            foreach (data_get($data,'product_items') as $value) {
                $is_check = filter_var(data_get($value,'isCheck'),FILTER_VALIDATE_BOOLEAN);
                
                if (!$value['sku']){
                    $value['sku'] = generateSKU($product->category,data_get($product,'name'), data_get($value, 'variation',[]));
                }

                if ($is_check){
                    $new_item = $this->product_item_repo->create([
                        'product_id' => data_get($data,'product_id'),
                        'sku' => data_get($value,'sku'),
                        'price' => data_get($value,'price'),
                        'quantity' => data_get($value,'quantity'),
                    ]);

                    foreach (data_get($value,'variation') as $option) {
                        $key = data_get($option,'variation_id').'-'.data_get($option,'value');
                        $this->product_config_repo->create([
                            'product_item_id' => data_get($new_item,'id'),
                            'variation_option_id' => $variation_option_ids[$key]
                        ]);
                    }
                }
                
            }
            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(),'code' => $th->getCode()], 400);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $option = $this->option_repo->where('id',$id)->firstOrFail();
            $items = $option->productItems;
            foreach ($items as $value) {
                $this->product_config_repo->where('product_item_id',data_get($value,'id'))->delete();
                $value->delete();
            }
            $option->delete();
            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => $th->getCode()], 400);
        }
    }
}

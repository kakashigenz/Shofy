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

class VariationController extends Controller
{
    protected $repository;
    protected $option_repo;
    protected $product_item_repo;
    protected $product_config_repo;
    protected $product_repo;

    public function __construct(
        IVariationRepository $repository,
        IVariationOptionRepository $option_repo,
        IProductItemRepository $product_item_repo,
        IProductConfigurationRepo $product_config_repo,
        IProductRepository $product_repo)
    {
        $this->repository = $repository;
        $this->option_repo = $option_repo;
        $this->product_item_repo = $product_item_repo;
        $this->product_config_repo = $product_config_repo;
        $this->product_repo = $product_repo;
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
                'product_items' => 'array',
                'variation_options' => 'array',
                'variation' => 'array'
            ]);

            $variation_option_ids = [];
            $other_option = [];

            $product = $this->product_repo->where('id',data_get($data,'product_id'))->with('variations','productItem')->first();
            
            DB::beginTransaction();

            //xóa các biến thể cũ

            foreach ($product['productItem'] as $value) {
                $this->product_config_repo->where('product_item_id',data_get($value,'id'))->delete();
                $value->delete();
            }

            $variation = $this->repository->create([
                'product_id' => $data['product_id'],
                'name' => data_get($data,'variation.name')
            ]);

            foreach (data_get($product,'variations') as $value) {
                if (data_get($value,'id') != data_get($variation,'id')){
                    $other_option = array_merge($other_option,$value->option->toArray());
                }
            }   
            
            foreach ($other_option as $value) {
                $key = data_get($value,'variation_id') . '-' . data_get($value,'value');
                $variation_option_ids[$key] = data_get($value,'id');
            }

            foreach (data_get($data,'variation_options') as $value) {                
                $option = $this->option_repo->create([
                    'variation_id' => data_get($variation,'id'),
                    'value' => $value
                ]);
                //variation.id là id đại diện ở fe
                $key = data_get($data,'variation.id') . '-' . $value;
                $variation_option_ids[$key] = data_get($option,'id');
            }

            foreach ($data['product_items'] as $value) {

                $is_check = filter_var(data_get($value,'isCheck'),FILTER_VALIDATE_BOOLEAN);
                
                if (!$value['sku']){
                    $value['sku'] = generateSKU($product->category,data_get($product,'name'), data_get($value, 'variation',[]));
                }

                if ($is_check){
                    $item = $this->product_item_repo->create([
                        'product_id' => $data['product_id'],
                        'price' => data_get($value,'price'),
                        'quantity' => data_get($value,'quantity'),
                        'sku' => data_get($value,'sku')
                    ]);
    
                    foreach ($value['variation'] as $option) {
                        $key = data_get($option,'variation_id') . '-' . data_get($option,'value');
                       $this->product_config_repo->create([
                            'product_item_id' => data_get($item,'id'),
                            'variation_option_id' => $variation_option_ids[$key]
                       ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => $th->getCode()], 400);
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
                'name' => 'required',
                'category_id' => 'numeric|required'
            ]);

            $this->repository->update($data, $id);

            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => $th->getCode()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $variation = $this->repository->where('id',$id)->first();
            foreach ($variation['option'] as $key => $value) {

                if ($key > 0){
                    $items = $value->productItems;
                    foreach ($items as $item) {
                        $this->product_config_repo->where('product_item_id',data_get($item,'id'))->delete();
                        $item->delete();
                    }
                }
                else{
                    $this->product_config_repo->where('variation_option_id',data_get($value,'id'));
                }
                $this->option_repo->delete(data_get($value,'id'));
            }
            $this->repository->destroy($id);
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['code' => $th->getCode(), 'message' => $th->getMessage()], 400);
        }
    }
}

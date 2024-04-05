<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\IAttributeProductRepository;
use App\Repositories\IProductConfigurationRepo;
use App\Repositories\IProductImageRepository;
use App\Repositories\IProductItemRepository;
use App\Repositories\IProductRepository;
use App\Repositories\IVariationOptionRepository;
use App\Repositories\IVariationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use function App\Helpers\generateSKU;

class ProductController extends Controller
{
    protected $product_repo;
    protected $variation_opt_repo;
    protected $product_item_repo;
    protected $product_config_repo;
    protected $variation_repo;
    protected $product_image_repo;
    protected $attribute_product_repo;

    public function __construct(
        IProductRepository $product_repo,
        IVariationOptionRepository $variation_opt_repo,
        IProductItemRepository $product_item_repo,
        IProductConfigurationRepo $product_config_repo,
        IVariationRepository $variation_repo,
        IProductImageRepository $product_image_repo,
        IAttributeProductRepository $attribute_product_repo
    ) {
        $this->product_repo = $product_repo;
        $this->variation_opt_repo = $variation_opt_repo;
        $this->product_item_repo = $product_item_repo;
        $this->product_config_repo = $product_config_repo;
        $this->variation_repo = $variation_repo;
        $this->product_image_repo = $product_image_repo;
        $this->attribute_product_repo = $attribute_product_repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search');
        $data = $this->product_repo->getList($search, $start, $length);
        $total = $this->product_repo->all($search)->count();
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
        try {
            $product = $request->validate([
                'name' => 'required',
                'category_id' => 'numeric',
                'description' => 'nullable',
                'status' => Rule::in([Product::waiting,Product::show,Product::hidden]),
                'weight' => 'integer',
                'height' => 'nullable|integer',
                'length' => 'nullable|integer',
                'width' => 'nullable|integer',
            ]);
            
            //generate slug
            $product['slug'] = $this->getSlug($product['name']);

            #cast show value from string to boolean
            // $product['show'] = filter_var($product['show'],FILTER_VALIDATE_BOOLEAN);

            $variation = $request->validate(['variation' => 'required']);
            $variation = json_decode($variation['variation'], true);
            $product_item = $request->validate(['product_item' => 'required']);
            $product_item = json_decode($product_item['product_item'], true);


            DB::beginTransaction();
            $new_product = $this->product_repo->create($product);
            if ($request->hasFile('thumb')) {
                $thumb = $request->file('thumb');
                $thumbUrl = $thumb->storeAs('image', Str::random(10) . '.' . $thumb->getClientOriginalExtension(), 'public');
                $new_product->thumb = $thumbUrl;
                $new_product->save();
            }
            
            $total_image = (int)($request->validate(['total_image' => 'numeric'])['total_image']);

            for ($i = 0 ;$i < $total_image; $i++){
                if ($request->hasFile("product_image_$i")) {
                    $image = $request->file("product_image_$i");
                    $imageUrl = $image->storeAs('image', Str::random(10) . '.' . $image->getClientOriginalExtension(), 'public');
                    $this->product_image_repo->create([
                        'path' => $imageUrl,
                        'product_id' => $new_product['id']
                    ]);
                }
                
            }

            $attribute_values= json_decode($request->input('attribute_values'));

            foreach ($attribute_values as $value) {
                $this->attribute_product_repo->create([
                    'product_id' => $new_product['id'],
                    'attribute_value_id' => $value
                ]);
            }

            

            $id_variation_options = [];

            foreach ($variation as $key => $item) {
                $new_variation = $this->variation_repo->create([
                    'name' => data_get($item,'name'),
                    'product_id' => data_get($new_product,'id')
                ]);

                foreach ($item['option'] as $each) {
                    $option = $this->variation_opt_repo->create([
                        'variation_id' => $new_variation['id'],
                        'value' => $each
                    ]);
                    $id_variation_options[$key.'-'.$each] = data_get($option, 'id');
                }
            }

            
            foreach ($product_item as $value) {
                $is_check = filter_var(data_get($value,'isCheck'),FILTER_VALIDATE_BOOL);
                if ($is_check){


                    if (!$value['sku']) {
                        $sku = generateSKU($new_product->category,$new_product['name'], data_get($value, 'variation',[]));
                        $value['sku'] = $sku;
                    }

    
                    $new_item = $this->product_item_repo->create(
                        [
                            'sku' => $value['sku'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'product_id' => data_get($new_product, 'id'),
                        ]
                    );

    
    
                    // if ($request->hasFile("product_image_$key")) {
                    //     $image = $request->file("product_image_$key");
                    //     $imageUrl = $image->storeAs('image', Str::random(10) . '.' . $image->getClientOriginalExtension(), 'public');
                    //     $new_item->image = $imageUrl;
                    //     $new_item->save();
                    // }

                    if (data_get($value,'variation')){
                        foreach ($value['variation'] as $item) {
                            $key = data_get($item,'variation_id') . '-' . data_get($item,'value');
                            $this->product_config_repo->create([
                                'product_item_id' => data_get($new_item, 'id'),
                                'variation_option_id' => $id_variation_options[$key]
                            ]);
                        }
                    }
                }
            }

                

            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->product_repo->show($id);
        $product['variations'] = $this->variation_repo->where('product_id',$id)->with('option')->get();
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = json_decode($request->input('data'), true);
            $deleted = data_get($data, 'thumb_deleted');
            if ($deleted) {
                if (Storage::exists('public/image/' . $deleted)) {
                    Storage::delete('public/image/' . $deleted);
                }
                $data['thumb'] = '';
            }
            if ($request->hasFile('file_thumb')) {
                $file = $request->file('file_thumb');
                $ext = $file->getClientOriginalExtension();
                $url = $file->storeAs('image', Str::random(10) . '.' . $ext, 'public');
                $data['thumb'] = $url;
            }

            $this->product_repo->update($id, [
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'thumb' => $data['thumb'],
                'description' => $data['description'],
                'weight' => $data['weight'],
                'height' => $data['height'],
                'length' => $data['length'],
                'width' => $data['width'],
                // 'status' => $data['status']
            ]);

            foreach ($data['images_deleted'] as $key => $value) {
                if (Storage::exists('public/image/' . $value)) {
                    Storage::delete('public/image/' . $value);
                }
                $this->product_image_repo->where('path','image/'.$value)->delete();
            }

            $total_image = (int)($request->validate(['total_image' => 'integer'])['total_image']);
            for ($i = 0;$i < $total_image;$i++){
                if ($request->hasFile("file_image_$i")){
                    $file = $request->file("file_image_$i");
                    $ext = $file->getClientOriginalExtension();
                    $url = $file->storeAs('image', Str::random(10) . '.' . $ext, 'public');
                    $this->product_image_repo->create([
                            'product_id' => $data['id'],
                            'path' => $url
                        ]
                    );
                }
            }

            foreach ($data['attribute_values'] as $value) {
                $this->attribute_product_repo->where('product_id',$value['pivot']['product_id'])
                ->where('attribute_value_id',$value['id'])
                ->update([
                    'attribute_value_id' => $value['pivot']['attribute_value_id']
                ]);
            }

            foreach ($data['product_item'] as $key => $value) {
                $this->product_item_repo->update($value['id'], [
                    'sku' => $value['sku'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],
                ]);

                // foreach (data_get($value, 'variation_option') as $config) {
                //     $this->product_config_repo->update(
                //         data_get($value, 'id'),
                //         data_get($config, 'pivot.variation_option_id'),
                //         [
                //             'product_item_id' => data_get($value, 'id'),
                //             'variation_option_id' => data_get($config, 'id')
                //         ]
                //     );
                // }
            }
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
            DB::beginTransaction();
            $product = $this->product_repo->show($id);
            $images = $product->productImage;
            foreach ($images as $image) {
                if (Storage::exists($image['path'])){
                    Storage::delete($image['path']);
                }
                $image->delete();
            }

            $product_items = $product->productItem;
            foreach ($product_items as $item) {
                $item->variationOption()->detach();
                $item->delete();
            }

            $variations = $product->variations;
            foreach ($variations as $variation) {
                $variation->option()->delete();
                $variation->delete();
            }

            $product->delete();
            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => $th->getCode()], 400);
        }
    }

    public function changeStatus(Request $request,$id){
        try {
            $status = $request->validate(['status' => 'integer']);
            $this->product_repo->update($id,$status);
            return response()->json(['message'=>'success']); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    private function getSlug($product_name){
        $slug = Str::slug($product_name);
        $count_slug = $this->product_repo->where('slug',$slug)->withTrashed()->count();
        if ($count_slug > 0){
            $slug .= Str::random(7);
        }
        return $slug;
    }
}

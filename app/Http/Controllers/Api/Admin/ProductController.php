<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\IProductConfigurationRepo;
use App\Repositories\IProductItemRepository;
use App\Repositories\IProductRepository;
use App\Repositories\IVariationOptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $product_repo;
    protected $variation_opt_repo;
    protected $product_item_repo;
    protected $product_config_repo;

    public function __construct(
        IProductRepository $product_repo,
        IVariationOptionRepository $variation_opt_repo,
        IProductItemRepository $product_item_repo,
        IProductConfigurationRepo $product_config_repo
    ) {
        $this->product_repo = $product_repo;
        $this->variation_opt_repo = $variation_opt_repo;
        $this->product_item_repo = $product_item_repo;
        $this->product_config_repo = $product_config_repo;
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
        // dd('post');
        try {
            $product = $request->validate([
                'name' => 'required',
                'slug' => 'unique:products,slug',
                'category_id' => 'numeric',
                'description' => 'nullable',
                'show' => Rule::in(['true', 'false'])
            ]);
            #cast show value from string to boolean
            $product['show'] = filter_var($product['show'],FILTER_VALIDATE_BOOLEAN);

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

            $id_variation_options = [];

            foreach ($variation as $item) {
                foreach ($item['option'] as $each) {
                    $option = $this->variation_opt_repo->create([
                        'variation_id' => $item['variation'],
                        'value' => $each
                    ]);
                    $id_variation_options[data_get($option, 'value')] = data_get($option, 'id');
                }
            }

            foreach ($product_item as $key => $value) {
                if (!$value['sku']) {
                    $sku = $this->generateSKU($new_product['name'], data_get($value, 'variation'));
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

                if ($request->hasFile("product_image_$key")) {
                    $image = $request->file("product_image_$key");
                    $imageUrl = $image->storeAs('image', Str::random(10) . '.' . $image->getClientOriginalExtension(), 'public');
                    $new_item->image = $imageUrl;
                    $new_item->save();
                }

                foreach ($value['variation'] as $item) {
                    $this->product_config_repo->create([
                        'product_item_id' => data_get($new_item, 'id'),
                        'variation_option_id' => $id_variation_options[$item]
                    ]);
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
        return $this->product_repo->show($id);
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
                'slug' => $data['slug'],
                'show' => $data['show']
            ]);

            foreach ($data['product_item'] as $key => $value) {
                $deleted_item = data_get($data, "product_image_delete_$key");
                if ($deleted_item) {
                    if (Storage::exists('public/image/' . $deleted_item)) {
                        Storage::delete('public/image/' . $deleted_item);
                    }
                    $value['image'] = '';
                }

                if ($request->hasFile("file_item_$key")) {
                    $file = $request->file("file_item_$key");
                    $ext = $file->getClientOriginalExtension();
                    $url = $file->storeAs('image', Str::random(10) . '.' . $ext, 'public');
                    $value['image'] = $url;
                }

                $this->product_item_repo->update($value['id'], [
                    'SKU' => $value['SKU'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],
                    'image' => $value['image'],
                    'show' => $value['show'],
                ]);

                foreach (data_get($value, 'variation_option') as $config) {
                    $this->product_config_repo->update(
                        data_get($value, 'id'),
                        data_get($config, 'pivot.variation_option_id'),
                        [
                            'product_item_id' => data_get($value, 'id'),
                            'variation_option_id' => data_get($config, 'id')
                        ]
                    );
                }
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
            $this->product_repo->destroy($id);
            return response()->json(['message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => $th->getCode()], 400);
        }
    }

    private function generateSKU($name, $variation)
    {
        $sku = substr($name, 0, 2);
        foreach ($variation as $value) {
            $sku .= $value[0];
        }
        return $sku;
    }
}

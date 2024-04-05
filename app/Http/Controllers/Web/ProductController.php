<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\IProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product_repo;

    public function __construct(IProductRepository $product_repo) {
        $this->product_repo = $product_repo;
    }
    public function show(string $slug){
        $product = $this->product_repo->where('slug',$slug)->with(['variations.option','productItem.variationOption'])->firstOrFail();
        return view('product.show',['product' => $product,'title' => $product['name']]);
    }
}

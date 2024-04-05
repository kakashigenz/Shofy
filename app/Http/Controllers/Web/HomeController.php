<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\ICategoryRepository;
use App\Repositories\IProductRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $product_repo;
    protected $category_repo;

    public function __construct(IProductRepository $product_repo,ICategoryRepository $category_repo) {
        $this->product_repo = $product_repo;
        $this->category_repo = $category_repo;
    }

    public function index(){
        // $categories = $this->category_repo->getList(0,'',1,10);
        // dd($categories);
        $products = $this->product_repo->getList('',0,10);
        foreach ($products as $product)  {
            $min_price = PHP_INT_MAX;
            $max_price = PHP_INT_MIN;
            foreach ($product['productItem'] as $item) {
                $min_price = min($item['price'],$min_price);
                $max_price = max($item['price'],$max_price);
            }
            $product['min_price'] = $min_price;
            $product['max_price'] = $max_price;
        }
        return view('home.index',['products' => $products,'title' => 'Shopfy - Mua sắm trực tuyến một cách nhanh chóng']);
    }
}

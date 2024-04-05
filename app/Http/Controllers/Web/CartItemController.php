<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\ICartItemRepository;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected $cart_item_repo;

    public function __construct(ICartItemRepository $cart_item_repo) {
        $this->cart_item_repo = $cart_item_repo;
    }

    public function show(Request $request){
        $user = auth()->guard('sanctum')->user();
        if (!$user){
            return redirect()->route('login');
        }
        $items = $this->cart_item_repo->where('user_id',$user->id)->with(['productItem.product','productItem.variationOption'])->get();
        return view('cart.show',['items' => $items,'title'=>'Giỏ Hàng']);
    }
}

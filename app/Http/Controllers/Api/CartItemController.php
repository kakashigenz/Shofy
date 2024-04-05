<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ICartItemRepository;
use App\Repositories\IProductItemRepository;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    
    protected $cart_item_repo;
    protected $product_item_repo;

    public function __construct(ICartItemRepository $cart_item_repo,IProductItemRepository $product_item_repo) {
        $this->cart_item_repo = $cart_item_repo;
        $this->product_item_repo = $product_item_repo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth('sanctum')->user();
            $items = $this->cart_item_repo->where('user_id',$user->id)->with(['productItem.product'])->get();
            return response()->json(['items' => $items],200);
        } catch (\Throwable $th) {
            return response()->json(['message',$th->getMessage()],400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'integer',
                'quantity' => 'integer'
            ]);

            $user = auth()->user();
    
            $item = $this->product_item_repo->show($data['id']);
            if ($data['quantity'] > $item['quantity']){
                throw new \Exception('Số lượng không hợp lệ');
            }

            $cart_item = $this->cart_item_repo->where('user_id',$user->id)->where('product_item_id',$data['id'])->first();

            if ($cart_item){
                $cart_item->quantity += $data['quantity'];
                $cart_item->save();
                return response()->json(['message' => 'success'],200);
            }

            $this->cart_item_repo->create([
                'user_id' => $user->id,
                'product_item_id' => $data['id'],
                'quantity' => $data['quantity']
            ]);
            return response()->json(['message' => 'success'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()],400);
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
                'quantity' => 'integer'
            ]);
            $this->cart_item_repo->update($id,$data);
            $res = $this->cart_item_repo->where('id',$id)->first();
            return response()->json(['message'=>'success','data' => $res],200);
        } catch (\Throwable $th) {
            return response()->json(['message'=> $th->getMessage()],400);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->cart_item_repo->delete($id);
            return response()->json(['message'=>'success']);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function buyNow(Request $request){
        try {
            $user = auth('sanctum')->user();
            $data = $request->validate([
                'id' => 'integer',
                'quantity' => 'integer'
            ]);
            
            $items = $this->cart_item_repo->where('user_id',$user->id)->get();
            $new_item = null;
            foreach ($items as $value) {
                if ($value['product_item_id'] == $data['id']){
                    $new_item = $value;
                }
            }
            if ($new_item){
                $new_item->quantity += $data['quantity'];
                $new_item->save();
            }
            else{
                $new_item = $this->cart_item_repo->create([
                    'user_id' => $user->id,
                    'product_item_id' => $data['id'],
                    'quantity' => $data['quantity'],
                ]);
            }
            $new_item->checked = true;
            $items[] = $new_item;
            
            return response()->json(['data' => $items,'message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()],400);
        }
    }
}

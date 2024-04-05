<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    protected $order_repo;

    public function __construct(OrderRepository $order_repo) {
        $this->order_repo = $order_repo;
    }

    public function store(Request $request){
        $data = $request->validate([
            'items' => 'array',
        ]);

        $order = $this->order_repo->create([
            'user_id' => auth()->user()->id,
        ]);
    }
}

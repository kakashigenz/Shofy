<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','product_item_id','quantity'];

    protected $hidden = ['user_id'];

    public function productItem(){
        return $this->belongsTo(ProductItem::class,'product_item_id','id');
    }
}

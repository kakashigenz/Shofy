<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductItem extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['product_id', 'sku', 'price', 'quantity', 'image'];
    protected $casts = ['show' => 'boolean'];
    // protected $dates = ['deleted_at'];


    public function variationOption()
    {
        return $this->belongsToMany(VariationOption::class, 'product_configurations', 'product_item_id', 'variation_option_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}

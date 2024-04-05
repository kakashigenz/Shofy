<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariationOption extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['variation_id', 'value'];
    protected $dates = ['deleted_at'];

    public function productItems(){
        return $this->belongsToMany(ProductItem::class,'product_configurations','variation_option_id','product_item_id');
    }
}

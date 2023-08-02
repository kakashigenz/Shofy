<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'slug', 'category_id', 'thumb', 'description', 'show'];

    protected $casts = [
        'show' => 'boolean',
    ];

    // protected $dates = ['deleted_at'];

    public function productItem()
    {
        return $this->hasMany(ProductItem::class, 'product_id', 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
}

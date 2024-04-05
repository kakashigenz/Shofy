<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    const waiting = 0;
    const show = 1;
    const hidden = 2;


    protected $fillable = ['name', 'slug', 'category_id', 'thumb', 'description', 'status','weight','height','length','width','note'];

    // protected $casts = [
    //     'show' => 'boolean',
    // ];

    

    // protected $dates = ['deleted_at'];

    public function productItem()
    {
        return $this->hasMany(ProductItem::class, 'product_id', 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function variations(){
        return $this->hasMany(Variation::class,'product_id','id');
    }

    public function productImage(){
        return $this->hasMany(ProductImage::class,'product_id','id');
    }

    public function attributeValues(){
        return $this->belongsToMany(AttributeValue::class,'products_attributes','product_id','attribute_value_id');
    }
}

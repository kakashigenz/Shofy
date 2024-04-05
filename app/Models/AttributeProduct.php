<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'products_attributes';

    protected $fillable = ['product_id','attribute_value_id'];
}

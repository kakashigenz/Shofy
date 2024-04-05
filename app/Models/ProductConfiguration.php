<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductConfiguration extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['product_item_id', 'variation_option_id'];
    protected $dates = ['deleted_at'];
}

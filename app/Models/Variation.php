<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','product_id'];
    protected $dates = ['deleted_at'];

    public function option()
    {
        return $this->hasMany(VariationOption::class, 'variation_id', 'id');
    }
}

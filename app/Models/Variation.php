<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id'];
    protected $dates = ['deleted_at'];

    public function option()
    {
        return $this->hasMany(VariationOption::class, 'variation_id', 'id');
    }
}

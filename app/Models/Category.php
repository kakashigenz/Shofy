<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'slug', 'parent_category_id'];
    // protected $dates = ['deleted_at'];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id');
    }

    public function variation()
    {
        return $this->hasMany(Variation::class, 'category_id', 'id');
    }
}

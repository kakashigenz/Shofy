<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','name','phone','city_code','district_code','ward_code','address','is_default'];

    protected $casts = ['is_default'];

    protected $hidden = ['user_id'];
}

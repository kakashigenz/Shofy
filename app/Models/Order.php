<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    const cancelled = 0;
    const waiting = 1;
    const accepted = 2;
    const shipping = 3;
    const received = 4;

    protected $fillable = ['user_id','address','total','status'];
}

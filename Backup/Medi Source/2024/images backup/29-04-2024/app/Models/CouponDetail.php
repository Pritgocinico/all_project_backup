<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function productDetail(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}

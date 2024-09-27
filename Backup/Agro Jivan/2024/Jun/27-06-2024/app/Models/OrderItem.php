<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function productDetail(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function varientDetail(){
        return $this->hasOne(ProductVariant::class,'id','variant_id');
    }
    public function categoryDetail(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function schemeDetail(){
        return $this->hasOne(Discount::class,'discount_code','discount_code');
    }
    public function orderDetail(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}

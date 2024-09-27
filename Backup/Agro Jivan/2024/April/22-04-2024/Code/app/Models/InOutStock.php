<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InOutStock extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = "in_out_stock";
    protected $guarded = ['id'];

    public function orderDetail(){
        return $this->hasOne(Order::class,'id','order_id');
    }

    public function productDetail(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function variantDetail(){
        return $this->hasOne(ProductVariant::class,'id','variant_id');
    }
}

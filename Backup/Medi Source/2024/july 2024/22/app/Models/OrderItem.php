<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function productDetail(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function orderProductItemDetail(){
        return $this->hasOne(OrderProductPdfDetail::class,'order_item_id','id');
    }
}

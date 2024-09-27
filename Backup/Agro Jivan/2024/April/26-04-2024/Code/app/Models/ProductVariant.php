<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'product_variant';

    protected $guarded = ['id'];
    
    public function getProductDetail(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    
    public function orderItems(){
        return $this->hasMany(OrderItem::class,'variant_id','id');
    }
}

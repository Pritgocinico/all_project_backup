<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "discount_item";

    protected $guarded = ['id'];

    public function productDetail(){
        return $this->hasOne(ProductVariant::class,'id','product_id');
    }
    public function freeProductDetail(){
        return $this->hasOne(ProductVariant::class,'id','free_product_id');
    }
    
    
    public function schemeDetail(){
        return $this->hasOne(Discount::class,'id','discount_id');
    }
}

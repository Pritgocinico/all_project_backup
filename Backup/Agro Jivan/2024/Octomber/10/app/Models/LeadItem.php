<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ="lead_item";

    protected $guarded = ['id'];

    public function productDetail(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function variantDetail(){
        return $this->hasOne(ProductVariant::class,'id','variant_id');
    }
    public function categoryDetail(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}

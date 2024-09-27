<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $guarded = ['id'];

    public function categoryDetail(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function productVariantDetail(){
        return $this->hasMany(ProductVariant::class,'product_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'discount';

    protected $guarded = ['id'];

    public function discountItemDetail(){
        return $this->hasMany(DiscountItem::class,'discount_id','id');
    }
    public function discountTypeDetail(){
        return $this->hasOne(DiscountType::class,'id','discount_type_id');
    }
}

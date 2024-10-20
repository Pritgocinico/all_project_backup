<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProduct extends Model
{
    use HasFactory,SoftDeletes;

    public function leadProduct(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}

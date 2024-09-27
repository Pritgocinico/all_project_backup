<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderItemDetail(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function orderPdfDetail(){
        return $this->hasOne(OrderPdfDetail::class,'order_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

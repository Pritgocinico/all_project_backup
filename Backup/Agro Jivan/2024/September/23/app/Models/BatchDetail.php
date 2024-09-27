<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "batch_detail";
    protected $guarded = ["id"];

    public function orderDetail(){
        return $this->hasOne(Order::class,'id','order_id');
    }
    public function orderItemDetail(){
        return $this->hasMany(OrderItem::class,'order_id','order_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

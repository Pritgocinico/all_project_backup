<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFeedback extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "order_feedback";

    protected $guarded = ['id'];

    public function orderDetail(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}

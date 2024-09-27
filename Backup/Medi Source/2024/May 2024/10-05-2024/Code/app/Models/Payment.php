<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'stripe_token',
        'amount',
        'currency',
        'status',
        'order_id'       
    ];

    public function orderDetail(){
        return $this->hasOne(Order::class,'id','order_id');
    }
    
}

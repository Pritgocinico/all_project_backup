<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'team_detail';

    protected $guarded = ['id'];

    public function userDetail(){
        return $this->hasOne(User::class,'id','user_id');
    }
    
    public function confirmOrder(){
        return $this->hasMany(Order::class,'created_by','user_id')->where('order_status',3);
    }
    public function pendingOrder(){
        return $this->hasMany(Order::class,'created_by','user_id')->where('order_status',1);
    }
    public function deliveredOrder(){
        return $this->hasMany(Order::class,'created_by','user_id')->where('order_status',6);
    }
    public function cancelOrder(){
        return $this->hasMany(Order::class,'created_by','user_id')->where('order_status',4);
    }
    public function returnOrder(){
        return $this->hasMany(Order::class,'created_by','user_id')->where('order_status',5);
    }
    public function allOrder(){
        return $this->hasMany(Order::class,'created_by','user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

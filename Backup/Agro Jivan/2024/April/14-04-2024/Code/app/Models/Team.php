<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'team';

    protected $guarded = ['id'];

    public function managerDetail(){
        return $this->hasOne(User::class,'id','manager_id');
    }
    public function teamMember(){
        return $this->hasMany(TeamDetail::class,'team_id','id');
    }
    
     public function teamUser(){
        return $this->hasMany(TeamDetail::class);
    }

    public function confirmOrder(){
        return $this->hasMany(Order::class,'created_by','manager_id')->where('order_status',3);
    }
    public function pendingOrder(){
        return $this->hasMany(Order::class,'created_by','manager_id')->where('order_status',1);
    }
    public function deliveredOrder(){
        return $this->hasMany(Order::class,'created_by','manager_id')->where('order_status',6);
    }
    public function cancelOrder(){
        return $this->hasMany(Order::class,'created_by','manager_id')->where('order_status',4);
    }
    public function returnOrder(){
        return $this->hasMany(Order::class,'created_by','manager_id')->where('order_status',5);
    }
    public function allOrder(){
        return $this->hasMany(Order::class,'created_by','manager_id');
    }

    public function teamDetails()
    {
        return $this->hasMany(TeamDetail::class);
    }
}

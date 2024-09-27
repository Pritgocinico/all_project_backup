<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ["id"];

    public function orderItem(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function districtDetail(){
        return $this->hasOne(SubLocation::class,'district','district');
    }
    
    public function userDetail(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function subDistrictDetail(){
        return $this->hasOne(SubLocation::class,'sub_district','sub_district');
    }

    public function villageDetail(){
        return $this->hasOne(SubLocation::class,'village_code','village');
    }
    public function stateDetail(){
        return $this->hasOne(Location::class,'id','state');
    }
    public function stockDetail(){
        return $this->hasMany(InOutStock::class,'order_id','id');
    }
    public function feedbackDetail(){
        return $this->hasOne(OrderFeedback::class,'order_id','id');
    }

    public function numberOrder(){
        return $this->hasMany(Order::class,'phoneno','phoneno')->where('order_status','6');

    }
    public function completeOrder(){
        return $this->hasMany(Order::class,'created_by','created_by')->where('order_status','5');
    }

    public function confirmUserDetail(){
        return $this->hasOne(User::class,'id','confirm_by');
    }
}

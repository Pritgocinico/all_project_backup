<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessRequest extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function businessDetail(){
        return $this->hasOne(Business::class,'id','business_id');
    }
    public function clientDetail(){
        return $this->hasOne(User::class,'id','client_id');
    }
    public function planDetail(){
        return $this->hasOne(Plan::class,'id','plan_id');
    }
}

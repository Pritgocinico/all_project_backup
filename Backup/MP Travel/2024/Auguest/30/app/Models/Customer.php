<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function roleDetail(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function userDetail(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function leadDetail(){
        return $this->hasMany(Lead::class,'customer_id','id');
    }
    public function cityDetail(){
        return $this->hasOne(City::class,'id','city');
    }
    public function stateDetail(){
        return $this->hasOne(State::class,'id','state');
    }
    public function countryDetail(){
        return $this->hasOne(Country::class,'iso2','country');
    }
    public function servicePreferenceTagDetail(){
        return $this->hasMany(ServicePreferenceTag::class,'customer_id','id');
    }
    public function moduleModifiedLog(){
        return $this->hasOne(ModuleModifiedLogs::class,'module_task_id','id')->where('module_name','customer')->latest();
    }
}

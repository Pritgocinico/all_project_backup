<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePreferenceTag extends Model
{
    use HasFactory,SoftDeletes;

    public function userDetail(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function servicePreferenceDetail(){
        return $this->hasOne(ServicePreference::class,'id','service_preference_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function userDetail(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function createDetail(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function leaveComment(){
        return $this->hasMany(LeaveComments::class,'leave_id','id');
    }
}

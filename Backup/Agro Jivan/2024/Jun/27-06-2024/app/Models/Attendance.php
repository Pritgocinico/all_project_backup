<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "attendances";
    protected $guarded = ['id'];

    public function breakLogDetail(){
        return $this->hasMany(BreakLog::class,'attendance_id','id');
    }
    public function userDetail(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

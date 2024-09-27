<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function breakLogDetail(){
        return $this->hasMany(BreakLog::class,'attendance_id','id');
    }
}

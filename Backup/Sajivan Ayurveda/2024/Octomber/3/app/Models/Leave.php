<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    static $total_paidLeave = 4;

    public function userDetail(){
        return $this->hasOne(User::class,'id','user_id');
    }
}

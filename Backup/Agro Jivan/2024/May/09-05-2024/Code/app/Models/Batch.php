<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "batch";
    protected $guarded = ["id"];

    public function driverDetail(){
        return $this->hasOne(User::class,'id','driver_id');
    }
    public function batchItemDetail(){
        return $this->hasMany(BatchDetail::class,'batch_id','id');
    }

    public function batchDetails()
    {
        return $this->hasMany(BatchDetail::class);
    }
}

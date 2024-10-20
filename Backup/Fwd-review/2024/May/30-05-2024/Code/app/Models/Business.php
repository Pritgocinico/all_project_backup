<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];
    public function client(){
        return $this->belongsTo('App\Models\User','client_id','id')->withDefault();
    }
    public function paymentDetail(){
        return $this->hasOne(Payment::class,'business_id','id')->withDefault();
    }
    
}

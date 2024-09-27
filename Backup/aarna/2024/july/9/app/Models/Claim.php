<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use HasFactory, SoftDeletes;
    
    public function policy(){
        return $this->belongsTo('App\Models\Policy','policy_id','id')->withDefault();
    }
}

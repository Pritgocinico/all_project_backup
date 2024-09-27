<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    use HasFactory;
    
    public function policy(){
        return $this->belongsTo('App\Models\Policy','policy_id','id')->withDefault();
    }
}

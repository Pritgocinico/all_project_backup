<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutListRecord extends Model
{
    use HasFactory;
    public function customers(){
        return $this->belongsTo('App\Models\Customer','customer','id')->withDefault();
    }
    public function policy(){
        return $this->belongsTo('App\Models\Policy','policy_id','id')->withDefault();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutList extends Model
{
    use HasFactory;

    public function agents(){
        return $this->belongsTo('App\Models\SourcingAgent','agent_id','id')->withDefault();
    }
}

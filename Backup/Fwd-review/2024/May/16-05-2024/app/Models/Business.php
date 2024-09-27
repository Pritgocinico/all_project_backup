<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public function client(){
        return $this->belongsTo('App\Models\User','client_id','id')->withDefault();
    }
}

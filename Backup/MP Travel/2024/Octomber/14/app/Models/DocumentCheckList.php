<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCheckList extends Model
{
    use HasFactory,SoftDeletes;

    public function userDetail(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function countryDetail(){
        return $this->belongsTo(Country::class,'country_code');
    }
}

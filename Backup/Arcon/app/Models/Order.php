<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Order extends Model
{
    use HasFactory;


    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('from-to', ""));

        if(count($date) != 2)
        {
            $date = [now()->subDays(29)->format("d-m-Y"), now()->format("d-m-Y")];
        }

        return $query->whereBetween('created_at', $date);
    }
    // public function user(){

    //     return $this
    //     ->belongsTo('App\Models\User')
    //     ->where('agent',Auth::user()->id)
    //     ->withTimestamps();
    // }
}

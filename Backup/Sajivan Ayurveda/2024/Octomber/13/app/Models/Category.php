<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function paginate($count = 10) {
        return $this->latest()->paginate($count);
    }


    public function userDetail(){
        return $this->belongsTo(User::class,'created_by');
    }
}

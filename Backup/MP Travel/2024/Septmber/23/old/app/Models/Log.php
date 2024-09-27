<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function userDetail()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

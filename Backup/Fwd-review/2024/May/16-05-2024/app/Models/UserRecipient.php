<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'user_id','first_name','last_name'
    ];
}

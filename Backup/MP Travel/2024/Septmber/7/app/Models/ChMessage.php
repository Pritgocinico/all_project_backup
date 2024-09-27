<?php

namespace App\Models;

use Chatify\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChMessage extends Model
{
    use UUID,SoftDeletes;
}

<?php

namespace App\Models;

use Chatify\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChFavorite extends Model
{
    use UUID,SoftDeletes;
}

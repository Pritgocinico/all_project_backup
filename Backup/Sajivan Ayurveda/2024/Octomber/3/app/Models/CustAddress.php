<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustAddress extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'add_type',
        'pin_code',
        'address',
        'village',
        'office_name',
        'dist_state',
    ];
}

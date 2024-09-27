<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BreakLog extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "break_log";

    protected $guarded = ['id'];
}

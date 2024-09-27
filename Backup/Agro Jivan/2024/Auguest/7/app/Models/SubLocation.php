<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLocation extends Model
{
    use HasFactory;

    protected $table='locations';
    protected $primarykey='id';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlassMeasurementFile extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'glass_measurement_files';
    protected $guarded =['id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measurement extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    
    public function project()
    {
        return $this->belongsTo(Project::class , 'project_id');
    }
    public function measurementfiles()
    {
        return $this->hasMany(Measurement::class);
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}

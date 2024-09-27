<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    
    // protected $fillable =['measuremet',];
    public function project()
    {
        return $this->belongsTo(Project::class , 'project_id');
    }
    public function measurement()
    {
        return $this->belongsTo(Project::class , 'measurement_id');
    }
}

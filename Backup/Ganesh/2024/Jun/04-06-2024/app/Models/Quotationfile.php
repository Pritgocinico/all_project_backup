<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotationfile extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    
    protected $fillable = ['project_id', 'quotation'];
    
    public function project()
    {
        return $this->belongsTo(Project::class , 'project_id');
    }
    public function quotation()
    {
        return $this->belongsTo(Project::class , 'quotation_id');
    }
}

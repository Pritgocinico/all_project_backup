<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'task',
        'project_id',
        'task_type',
        'task_status',
        'task_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}

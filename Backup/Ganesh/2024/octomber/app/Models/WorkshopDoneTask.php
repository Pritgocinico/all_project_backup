<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopDoneTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'project_id',
        'chk',
        'add_type',
        'issue_id',
    ];

    public function wquestion()
    {
        return $this->belongsTo(ProjectQuestion::class, 'question_id'); // Assuming 'Question' is the related model and 'question_id' is the foreign key
    }
}

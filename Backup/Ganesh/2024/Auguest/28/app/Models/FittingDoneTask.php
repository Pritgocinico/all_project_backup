<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FittingDoneTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'project_id',
        'chk',
        'add_type',
        'issue_id',
    ];

    public function question()
    {
        return $this->belongsTo(FittingQuestion::class, 'question_id'); // Assuming 'Question' is the related model and 'question_id' is the foreign key
    }
}

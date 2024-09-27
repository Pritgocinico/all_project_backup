<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QaDoneTask extends Model
{
    use HasFactory,SoftDeletes;

    public function question()
    {
        return $this->belongsTo(ProjectQuestion::class, 'question_id'); // Assuming 'Question' is the related model and 'question_id' is the foreign key
    }
}

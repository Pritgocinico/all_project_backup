<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalProjectDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function workshopIssueTask(){
        return $this->hasMany(WorkshopDoneTask::class,'issue_id','id');
    }
    public function fittingIssueTask(){
        return $this->hasMany(FittingDoneTask::class,'issue_id','id');
    }
}

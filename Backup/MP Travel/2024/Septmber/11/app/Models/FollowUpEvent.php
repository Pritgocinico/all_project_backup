<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUpEvent extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function leadDetail(){
        return $this->hasOne(Lead::class,'id','lead_id');
    }

    public function commentDetail(){
        return $this->hasMany(FollowUpComment::class,'follow_id','id');
    }
    public function userDetail(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function followUpMemberDetail(){
        return $this->hasMany(FollowUpMember::class,'followup_id','id');
    }
    public function subTaskData(){
        return $this->hasMany(FollowUpChecklistItem::class,'follow_up_id','id');
    }
}

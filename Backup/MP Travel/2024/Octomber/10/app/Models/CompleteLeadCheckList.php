<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompleteLeadCheckList extends Model
{
    use HasFactory,SoftDeletes;

    public function checkListItem(){
        return $this->hasOne(LeadCheckListItem::class,'id','checklist_id');
    }

    public function userDetail(){
        return $this->hasOne(User::class,'id','created_by');
    }
}

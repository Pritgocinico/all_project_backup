<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function departmentDetail(){
        return $this->belongsTo(Department::class,'department_id');
    }
    public function userDetail(){
        return $this->hasOne(User::class, 'id', 'emp_id');
    }

    public function ticketCommentDetail(){
        return $this->hasMany(TicketComment::class,'ticket_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory,SoftDeletes;

    protected $table='ticket';
    protected $guarded=['id'];

    public function userDetail(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function ticketCommentDetail(){
        return $this->hasMany(TicketComment::class,'ticket_id','id');
    }
}

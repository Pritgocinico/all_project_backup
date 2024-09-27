<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketComment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "ticket_comment";
    protected $guarded = ["id"];

    public function userDetail(){
        return $this->hasOne(User::class,'id','sent_by');
    }
}

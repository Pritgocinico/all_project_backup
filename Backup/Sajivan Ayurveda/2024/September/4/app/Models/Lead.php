<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory ,SoftDeletes;

    protected $guarded = ['id'];

    public function customerDetail(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function leadMemberDetail(){
        return $this->hasMany(LeadMember::class,'lead_id','id');
    }

    public function userDetail(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function leadTravelDetail(){
        return $this->hasMany(LeadTravelDetail::class,'lead_id','id');
    }
    public function leadAttachment(){
        return $this->hasMany(LeadAttachment::class,'lead_id','id');
    }
}

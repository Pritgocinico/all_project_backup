<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "lead";
    protected $guarded = ["id"];

    public function leadDetail(){
        return $this->hasMany(LeadItem::class,'lead_id','id');
    }
    public function districtDetail(){
        return $this->hasOne(SubLocation::class,'district','district');
    }
    public function subDistrictDetail(){
        return $this->hasOne(SubLocation::class,'sub_district','sub_district');
    }
    public function userDetail(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function villageDetail(){
        return $this->hasOne(SubLocation::class,'village_code','village');
    }
    public function stateDetail(){
        return $this->hasOne(Location::class,'id','state');
    }
}

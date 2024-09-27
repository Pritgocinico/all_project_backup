<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPermission extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function permissionName(){
        return $this->hasOne(Permission::class,'id','permission_id');
    }
    public function roleName(){
        return $this->hasOne(Role::class,'id','rolo_id');
    }
}

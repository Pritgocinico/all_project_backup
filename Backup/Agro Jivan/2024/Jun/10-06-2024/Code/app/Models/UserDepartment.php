<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDepartment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "user_department";

    protected $guarded = ["id"];

    public function departmentNameDetail(){
        return $this->hasOne(Department::class,'id','department_id');
    }
}

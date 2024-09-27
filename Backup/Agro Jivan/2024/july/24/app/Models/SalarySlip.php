<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalarySlip extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "salaryslips";

    protected $guarded = ['id'];

    public function userDetail(){
        return $this->hasOne(User::class,'id','emp_id');
    }
}

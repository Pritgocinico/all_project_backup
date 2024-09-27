<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalarySlip extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function employeeDetail(){
        return $this->belongsTo(User::class,'emp_id');
    }

    public function salarySlipDetailsForMonth($month,$year){
        return $this->hasMany(SalarySlipDetail::class,'emp_id','emp_id')
        ->where('month','=',$month)->where('year','<=',$year);
    }
}

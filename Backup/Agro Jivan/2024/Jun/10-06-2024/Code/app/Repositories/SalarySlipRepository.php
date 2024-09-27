<?php

namespace App\Repositories;

use App\Interfaces\SalarySlipRepositoryInterface;
use App\Models\SalarySlip;

class SalarySlipRepository implements SalarySlipRepositoryInterface 
{
    public function store($data){
        return SalarySlip::create($data);
    }

    public function getAllDetail($search){
        return SalarySlip::with('userDetail')->where('month','like','%'.$search.'%')->latest()->paginate(15);
    }

    public function getDetailById($id){
        return SalarySlip::with('userDetail')->where('id',$id)->first();
    }
}
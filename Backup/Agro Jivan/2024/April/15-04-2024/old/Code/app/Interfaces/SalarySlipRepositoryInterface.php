<?php

namespace App\Interfaces;

interface SalarySlipRepositoryInterface 
{
    public function store($data);
    public function getAllDetail($search);
    public function getDetailById($id);
}
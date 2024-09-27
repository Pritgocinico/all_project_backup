<?php

namespace App\Interfaces;

interface EmployeeSalaryRepositoryInterface
{
    public function store($data);

    public function getAllData();

    public function getUserData();

    public function getUserSalaryData();

    public function getDetailById($id);
    
    public function update($data,$where);

    public function delete($id);
}
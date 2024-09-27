<?php

namespace App\Interfaces;

interface DepartmentRepositoryInterface 
{
    public function store($data);

    public function getAllDepartment($search);
    
    public function getDetailById($id);
    
    public function update($data,$where);
    
    public function delete($id);
    
    public function getAllDepartmentWithPaginate();

    public function storeDepartmentUser($data);

    public function deleteUserDepartment($id);
}
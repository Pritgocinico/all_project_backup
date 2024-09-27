<?php

namespace App\Interfaces;

interface UserPermissionRepositoryInterface 
{
    public function storeEmployeePermission($data);
    
    public function getAllUserPermission($id);

    public function getDetailByUserId($id);

    public function update($where,$update);
}
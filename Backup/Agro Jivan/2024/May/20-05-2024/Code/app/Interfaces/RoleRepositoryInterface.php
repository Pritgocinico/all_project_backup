<?php

namespace App\Interfaces;

interface RoleRepositoryInterface 
{
    public function getAllRole();

    public function storeRoleUser($data);
    
    public function update($role,$whereRole);

    public function getDetailById($id);
}
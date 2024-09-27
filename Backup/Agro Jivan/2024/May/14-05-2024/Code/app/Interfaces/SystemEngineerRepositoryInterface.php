<?php

namespace App\Interfaces;

interface SystemEngineerRepositoryInterface 
{
    public function store($data);   

    public function getAllData($status,$role,$search,$type);
    
    public function getDetailById($id);
    
    public function update($data,$where);

    public function delete($id);
    
    public function getUserByMobileOrPhone($name);

}
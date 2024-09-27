<?php

namespace App\Interfaces;

interface InfoSheetRepositoryInterface 
{
    public function store($data);

    public function getAllData($search,$date,$type,$role);

    public function getDetailById($id);
    
    public function update($data,$where);

    public function delete($id);
}
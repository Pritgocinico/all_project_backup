<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface 
{
    public function getAllParentCategory();

    public function store($data);

    public function getAllCategory($search);

    public function getDetailById($id);
    
    public function update($data,$where);

    public function delete($id);

    public function checkCategoryIsExist($id);

    public function getAllCategoryWithChild();

    public function getAllCategoryExport($search);

    public function getAllData();
}
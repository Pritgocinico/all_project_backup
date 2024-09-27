<?php

namespace App\Interfaces;

interface EmployeeRepositoryInterface 
{
    public function store($data);   

    public function getAllData($status,$role,$search,$type);
    
    public function getDetailById($id);
    
    public function update($data,$where);

    public function delete($id);
    
    public function getUserByMobileOrPhone($name);

    public function getAllEmployee();

    public function getUserListWithAbsentPresentDetail($from,$to,$manager="");

    public function getVillages();
    
    public function employeeCount();
    
    public function getLastInsertId();
    public function getAllHR();

    public function getOtherHrDetail();

    public function getAllUsersIgnored($id);

    public function getShiftWiseEmployee($shift);
    public function getAllSystemEngineer();
    public function getAllDriver();

    public function getAllEmployeeSearch($search,$status);

    public function getEmployeeCount();
    public function getAllManagerList();

    public function getAllEmployeeTeam();

    public function getNotIncludeTeamEmployee($id);

    public function getTopFiveConfirmOrder();

    public function getAllDriverSearch($search);
}
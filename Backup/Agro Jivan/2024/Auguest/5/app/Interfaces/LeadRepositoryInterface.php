<?php

namespace App\Interfaces;

interface LeadRepositoryInterface 
{
    public function store($data);

    public function leadCount();

    public function getLastInsertId();

    public function storeLeadItem($data);

    public function getLeadDetails($type,$value);
    
    public function getAllLeadDetail($search,$date,$district);
    
    public function getAllLeadDetailExport($search,$date,$district);

    public function getUseDistrictLead();
    
    public function getDetailById($id);
}
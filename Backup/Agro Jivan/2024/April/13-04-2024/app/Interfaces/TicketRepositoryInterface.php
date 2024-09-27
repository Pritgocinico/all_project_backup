<?php

namespace App\Interfaces;

interface TicketRepositoryInterface 
{
    public function getAllTicket($search,$date,$type);
    
    public function store($data);
    
    public function getDataById($id);
    
    public function update($data,$where);
    
    public function delete($id);

    public function getAllTicketData($search,$type, $dateFilter, $userId);

    // public function getQuery($search,$type, $dateFilter, $userId);
    public function storeTicketComment($data);
    
    public function totalTicketCount($type);

    public function getSystemTicketList($search,$date,$empID,$type);
}
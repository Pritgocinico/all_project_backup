<?php

namespace App\Interfaces;

interface UserLogRepositoryInterface 
{
    public function getAllLog($search,$date);
}
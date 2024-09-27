<?php

namespace App\Interfaces;

interface TeamRepositoryInterface 
{
    public function getAllData($search,$type);

    public function generateTeamId();

    public function store($data);

    public function storeTeamDetail($data);

    public function getDetailById($id);

    public function update($data,$where);

    public function getTeamDetailById($id,$search);

    public function removeMember($where);

    public function getTopFiveUserTeam();
}
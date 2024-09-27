<?php

namespace App\Interfaces;

interface FeedbackRepositoryInterface
{
    public function getDetailByOrderId($id);
    public function store($data);
    public function update($updateDetail,$where);
    public function getAllData($search);
    public function totalOrderFeedback($date);
}
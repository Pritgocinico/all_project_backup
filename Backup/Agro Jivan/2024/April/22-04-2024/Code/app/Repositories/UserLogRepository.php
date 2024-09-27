<?php

namespace App\Repositories;

use App\Interfaces\UserLogRepositoryInterface;
use App\Models\Log;

class UserLogRepository implements UserLogRepositoryInterface
{
    public function getAllLog($search, $date)
    {
        return Log::with('userDetail')->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('module', 'like', '%' . $search . '%')
                    ->orWhere('log', 'like', '%' . $search . '%')
                    ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        })->when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->when(Auth()->user()->id !== 1, function ($query) {
                $query->where('user_id', Auth()->user()->id);
            })->latest()->paginate(15);
    }
}

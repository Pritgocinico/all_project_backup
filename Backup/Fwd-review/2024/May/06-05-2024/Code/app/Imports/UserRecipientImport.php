<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\UserRecipient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserRecipientImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        $user = User::where('id',Auth::user()->id)->first();
        $userArray  = [];
        $userArray ['id'] = $user->business;
        // dd($userArray ['id']);
        // dd($user, $user->business);
        return new UserRecipient([
            'user_id' => $user->business,
            'email' => $row['email'],
            'first_name' => $row['name'],
            'last_name' => $row['last_name'],
            'phone' => $row['phone'],
        ]);
    }
}

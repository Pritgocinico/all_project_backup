<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;

class MemberHelper
{

    public static function updateMember($id, $data)
    {
        // dd($id,$data);
        $apiUrl = env('MEMBER_PRESS_API_URL');
        $apiKey = env('MEMBER_PRESS_API_KEY');
        $endpointGet = "{$apiUrl}/members/{$id}";
        try {
            $response = Http::withHeaders([
                'MEMBERPRESS-API-KEY' => $apiKey,
            ])->post($endpointGet);
            if ($response->json()) {
                $updateMemberDetail = "{$apiUrl}/members/{$id}";
                $userDetail = User::where('member_id',$id)->first();
                $data['email'] = $userDetail->email;
                $data['user_name'] = $userDetail->email;
                try {
                    $response1 = Http::withHeaders([
                        'MEMBERPRESS-API-KEY' => $apiKey,
                    ])->post($updateMemberDetail,$data);
                    FacadesLog::info($response1->json());
                } catch (\Throwable $th1) {
                    FacadesLog::info($th1);
                }
            }
            FacadesLog::info($response->json());
        } catch (\Throwable $th) {
            FacadesLog::info($th);
        }
        return 1;
    }
}

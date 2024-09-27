<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;

class MemberHelper
{

    public static function updateMember($id, $data)
    {
        $apiUrl = env('MEMBER_PRESS_API_URL');
        $apiKey = env('MEMBER_PRESS_API_KEY');
        $endpointGet = "{$apiUrl}/members/{$id}";
        try {
            $response = Http::withHeaders([
                'MEMBERPRESS-API-KEY' => $apiKey,
            ])->post($endpointGet);
            if ($response->json()) {
                $updateMemberDetail = "{$apiUrl}/members/{$id}";
                $userDetail = User::where('member_id', $id)->first();
                $data['email'] = $userDetail->email;
                $data['user_name'] = $userDetail->email;
                try {
                    $response1 = Http::withHeaders([
                        'MEMBERPRESS-API-KEY' => $apiKey,
                    ])->post($updateMemberDetail, $data);
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
    public static function updateMemberPassword($id, $data1)
    {
        $apiUrl = "https://fwdreviews.com/wp-json/wp/v2/users/{$id}";
        $jwtToken = env('JWT_TOKEN');
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
                "Content-Type: application/json",
            ])->post($apiUrl, $data1);
            if ($response->json()) {
                FacadesLog::info($response->json());
            }
        } catch (\Throwable $th) {
            FacadesLog::info($th);
        }
        return 1;
    }

    public static function deleteMember($id)
    {

        $apiUrl = env('MEMBER_PRESS_API_URL');
        $apiKey = env('MEMBER_PRESS_API_KEY');
        $endpointGet = "{$apiUrl}/members/{$id}";
        $response = Http::withHeaders([
            'MEMBERPRESS-API-KEY' => $apiKey,
        ])->post($endpointGet);
        if ($response->json()) {
            $updateMemberDetail = "{$apiUrl}/members/{$id}";
            try {
                $response1 = Http::withHeaders([
                    'MEMBERPRESS-API-KEY' => $apiKey,
                ])->delete($updateMemberDetail);
                FacadesLog::info($response1->json());
            } catch (\Throwable $th1) {
                FacadesLog::info($th1);
            }
        }
    }
}

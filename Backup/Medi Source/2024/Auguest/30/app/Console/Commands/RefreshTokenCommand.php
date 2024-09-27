<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setting = Setting::first();
        $config = config('quickbooks');
        $refreshToken = $setting->refresh_token;
        $client_id = $config['client_id'];
        $client_secret = $config['client_secret'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $this->error("cURL Error #:" . $err);
        } else {
            $responseData = json_decode($response, true);
            $update['access_token'] = $responseData['access_token'];
            $update['refresh_token'] = $responseData['refresh_token'];
            Setting::where('id', 1)->update($update);
            Log::info('Refresh token successfully updated');
            $this->info('Refresh token successfully updated');
        }
        return 1;
    }
}

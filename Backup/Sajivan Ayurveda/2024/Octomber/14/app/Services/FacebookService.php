<?php

namespace App\Services;

use Facebook\Facebook;

class FacebookService
{
    protected $fb;

    public function __construct()
    {
        // Initialize the Facebook SDK
        $this->fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v17.0',
        ]);
    }

    public function getAdLeadForms($adId, $accessToken)
    {
        try {
            // Get leadgen forms for the specific ad
            $response = $this->fb->get("/$adId?fields=leadgen_forms", $accessToken);
            return $response->getDecodedBody();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            return ['error' => 'Graph API returned an error: ' . $e->getMessage()];
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            return ['error' => 'Facebook SDK returned an error: ' . $e->getMessage()];
        }
    }

    public function getLeadData($leadFormId, $accessToken)
    {
        try {
            // Get leads from the specific leadgen form
            $response = $this->fb->get("/$leadFormId/leads", $accessToken);
            return $response->getDecodedBody();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            return ['error' => 'Graph API returned an error: ' . $e->getMessage()];
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            return ['error' => 'Facebook SDK returned an error: ' . $e->getMessage()];
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class ReferooService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->baseUrl = 'https://api.sandbox.referoo.com.au'; // Sandbox URL
        $this->clientId = config('services.referoo.client_id');
        $this->clientSecret = config('services.referoo.client_secret');
        $this->redirectUri = config('services.referoo.redirect_uri');
    }

    public function buildAuthorizationUrl($state)
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'read',
            'response_type' => 'code',
            'state' => $state,
        ]);
        error_log("\n\nbuild auth url\n".$this->baseUrl."/oauth/authorize/?"."$query"."\n\n\n");
        return "{$this->baseUrl}/oauth/authorize/?{$query}";
    }

    // Get our initial Auth code from Referoo
    public function handleAuthorizationCallback($code)
    {
        $response = Http::asForm()->post("{$this->baseUrl}/oauth/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ]);
    
        if ($response->successful()) {
            $tokenData = $response->json();
            //error_log("n\n\naccess token data: " . print_r($tokenData, true)."\n\n\n"); //remove
            $this->storeTokens($tokenData);
        } else {
            // Log error details
            Log::error('Failed to get access token', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);
            throw new \Exception('Error obtaining access token.');
        }
    }
    
    public function hasAccessToken()
    {
        error_log("\n\n\nhas access token: " . print_r(Cache::has('referoo_access_token'), true)."\n\n\n"); //remove
        return Cache::has('referoo_access_token');
    }

    public function getCandidates($limit = 25, $offset = 0, $archived = 0)
    {
        $accessToken = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/oauth2/candidates", [
            'limit' => $limit,
            'offset' => $offset,
            'archived' => $archived,
        ]);
    
        //error_log("\n\n\ncandidate data: " . print_r($response->json(), true)."\n\n\n"); //remove
        return $response->json();
    }

    public function getCandidateDetails($num, $archived = 0)
    {
        $accessToken = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/oauth2/candidate/{$num}", [
            'archived' => $archived,
        ]);
        return $response->json();
    }

    public function getCandidateReferees($num)
    {
        error_log("\n\n\nreferee data - getting\n\n\n"); //remove
        $accessToken = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/oauth2/candidate/{$num}/referees", []);
        error_log("\n\n\nreferee data: " . print_r($response->json(), true)."\n\n\n"); //remove
        return $response->json();
    }

    public function getLoggedInUser()
    {
        $accessToken = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/oauth2/me", []);
        return $response;
    }

    protected function storeTokens($tokenData)
    {
        if (isset($tokenData['access_token'], $tokenData['refresh_token'], $tokenData['expires_in'])) {
            Cache::put('referoo_access_token', $tokenData['access_token'], $tokenData['expires_in'] - 30);
            Cache::put('referoo_refresh_token', $tokenData['refresh_token'], now()->addYear());
        } else {
            Log::error('Invalid token data received', ['tokenData' => $tokenData]);
            throw new \Exception('Invalid token data received.');
        }
    }

    protected function getAccessToken()
    {
        if (Cache::has('referoo_access_token')) {
            return Cache::get('referoo_access_token');
        }

        return $this->refreshAccessToken();
    }

    // Not needed for this assessment but I added to get the access token too.
    protected function refreshAccessToken()
    {

        // Retrieve the refresh token from the cache
        $refreshToken = Cache::get('referoo_refresh_token');
        
        // Check if the refresh token exists
        if (!$refreshToken) {
            Log::error('Refresh token not found.');
            header('Location: /');
            //throw new \Exception('Refresh token not found.');
        }
    
        // Make the request to refresh the access token
        $response = Http::asForm()->post("{$this->baseUrl}/oauth/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);
    
        if ($response->successful()) {
            $tokenData = $response->json();
            // Make sure to check if the expected keys exist in the response
            if (isset($tokenData['access_token'], $tokenData['refresh_token'])) {
                $this->storeTokens($tokenData);
                return $tokenData['access_token'];
            } else {
                Log::error('Invalid token data received from refresh.', ['tokenData' => $tokenData]);
                throw new \Exception('Invalid token data received from refresh.');
            }
        } else {
            // Log error details
            Log::error('Failed to refresh access token', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);
            throw new \Exception('Error refreshing access token.');
        }
    }

    public function logout()
    {
        // Remove the tokens from the cache
        Cache::forget('referoo_access_token');
        Cache::forget('referoo_refresh_token');

        // Check if tokens still exist
        if (Cache::has('referoo_access_token') || Cache::has('referoo_refresh_token')) {
            error_log('Tokens were not cleared.');
        } else {
            error_log('Tokens cleared successfully.');
        }
    }
    
    
}

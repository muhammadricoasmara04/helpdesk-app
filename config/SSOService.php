<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSOService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('services.kemen_sso');
    }

    /**
     * Request token SSO (password grant)
     */
    public function getToken($username, $password)
    {
        try {
            $url = rtrim($this->config['base_url'], '/')
                . "/realms/{$this->config['realm']}/protocol/openid-connect/token";

            return Http::asForm()->post($url, [
                'grant_type'    => 'password',
                'client_id'     => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'username'      => $username,
                'password'      => $password,
            ]);
        } catch (\Exception $e) {
            Log::error("SSO TOKEN ERROR: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ambil userinfo dari SSO
     */
    public function getUserInfo($accessToken)
    {
        $url = rtrim($this->config['base_url'], '/')
            . "/realms/{$this->config['realm']}/protocol/openid-connect/userinfo";

        return Http::withToken($accessToken)->get($url);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use GuzzleHttp\Client;

trait GiteeLoginTrait
{
    protected function getAuthUrl()
    {
        $client_id = config('services.gitee.client_id');
        $redirect_uri = config('services.gitee.redirect_uri');

        return "https://gitee.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code";
    }

    protected function getAccessToken($code)
    {
        // 实例化 HTTP 客户端
        $client = new Client;

        $data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('services.gitee.client_id'),
            'redirect_uri' => config('services.gitee.redirect_uri'),
            'client_secret' => config('services.gitee.client_secret')
        ];

        $url = 'https://gitee.com/oauth/token';

        $response = $client->request('POST', $url, [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function getUserByToken($accessToken)
    {
        $url = "https://gitee.com/api/v5/user?access_token=$accessToken";

        $client = new Client;

        $response = $client->request('GET', $url);

        return json_decode($response->getBody(), true);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    use GiteeLoginTrait;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return redirect($this->getAuthUrl());
    }

    public function handleProviderCallback()
    {
        // 根据回调链接附带的授权码 code，请求码云获取访问 token
        $token = $this->getAccessToken(request('code'));

        // 根据 token 去码云获取用户信息
        $user =  $this->getUserByToken($token['access_token']);

        dd($user);
    }
}

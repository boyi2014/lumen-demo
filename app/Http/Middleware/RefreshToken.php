<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class RefreshToken
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $this->auth->guard()->getToken()->refreshValidate();

        //刷新token,获得新token
        $newToken = $this->auth->guard()->refreshToken();

        $response = $next($request);

        if ($newToken) {
            $response->headers->set('Authorization', 'Bearer ' . $newToken);
            //刷新token，会添加到headers中
            //var_dump($response);
            //$response->setContent(['token'=>$newToken]);
        }

        return $response;
    }
}

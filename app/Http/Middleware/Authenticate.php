<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

use Lsxiao\JWT\Exception\BaseJWTException;
use Lsxiao\JWT\Exception\UnauthorizedException;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	try {
    		$this->auth->guard()->getToken()->validate();
    	} catch (BaseJWTException $e) {
    		echo '------------';
    		var_dump($e->getMessage());
    		//exit(0);
    	}
    	echo 222;
    	if ($this->auth->guard()->guest()) {
    		throw new UnauthorizedException('jwt authenticate failed');
    	}
    	
    	return $next($request);
    }
}

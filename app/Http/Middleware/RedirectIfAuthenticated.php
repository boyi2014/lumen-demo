<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next)
    {
		echo 222;
        $response = $next($request);
        echo 333;
        return $response;
    }
}

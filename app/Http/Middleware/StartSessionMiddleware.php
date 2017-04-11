<?php

namespace App\Http\Middleware;

use Closure;

class StartSessionMiddleware
{
    public function handle($request, Closure $next)
    {
    	//echo 'Middleware：startSession->start';
        $response = $next($request);
        //echo '<br/>';
        //echo 'Middleware：startSession->end';
        return $response;
    }
    
    public function terminate($request, $response)
    {
    	//echo '<br/>';
    	//echo 'Middleware：startSession->terminate<br/>';
    	//list($usec, $sec) = explode(" ", microtime());
    	//echo '总耗时：'.((float)$usec + (float)$sec);
    	//echo '=============';
    }
    
}

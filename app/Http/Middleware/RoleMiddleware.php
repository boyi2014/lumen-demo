<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role=null)
    {
//     	if(! $request->user()->hasRole($role) ){
//     		return redirect()->route('profile', ['id'=>3, 'test'=>'true']);
//     	}
		echo 'aaaa';
		echo $role;
        $response = $next($request);
        echo 'bbbb';
        return $response;
    }
    
    
}

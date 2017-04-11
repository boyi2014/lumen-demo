<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lsxiao\JWT\Auth\JWTGuard;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
    	$this->app['auth']->extend('jwt', function ($app, $name, array $config) {
    		$guard = new JWTGuard($app['auth']->createUserProvider($config['provider']), $app['request']);
    	
    		return $guard;
    	});
    }
    
    public function register()
    {
    	
    }
}

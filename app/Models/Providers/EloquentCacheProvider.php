<?php

namespace App\Models\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Contracts\IMeta;
use App\Models\Common\RedisMeta;

use App\Models\Contracts\ICache;
use App\Models\Common\RedisCache;

class EloquentCacheProvider extends ServiceProvider
{
	public function boot()
	{
		
	}
   
    public function register()
    {
    	//$this->app->bind('App\Models\Contracts\IMeta', 'App\Models\Common\RedisMeta');
    	//$this->app->bind('App\Models\Contracts\ICache', 'App\Models\Common\RedisCache');
    	
    	$this->app->singleton(IMeta::class, function () 
    	{
    		$redis = $this->app->make('redis');
    		return new RedisMeta( $redis );
    	});
    	
    	$this->app->singleton(ICache::class, function () 
    	{
    		$redis = $this->app->make('redis');
    		return new RedisCache( $redis );
    	});
    }
}

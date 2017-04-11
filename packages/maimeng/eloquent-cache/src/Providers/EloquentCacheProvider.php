<?php

namespace MaiMeng\EloquentCache\Providers;

use Illuminate\Support\ServiceProvider;

use MaiMeng\EloquentCache\Contracts\IMeta;
use MaiMeng\EloquentCache\Common\RedisMeta;

use MaiMeng\EloquentCache\Contracts\ICache;
use MaiMeng\EloquentCache\Common\RedisCache;

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

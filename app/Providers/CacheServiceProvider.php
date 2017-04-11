<?php 
	namespace App\Providers;
	
	use Illuminate\Support\ServiceProvider;
	use Illuminate\Support\Facades\Cache;
	use TargetLiu\PHPRedis\Cache\PHPRedisStore;
	
	class CacheServiceProvider extends ServiceProvider
	{
		public function boot()
		{
			Cache::extend('phpredis', function($app)
			{
				return Cache::repository(new PHPRedisStore($app->make('phpredis'), $app->config['cache.prefix']));
			});
		}
		
		public function register()
		{
			
		}
	}

?>
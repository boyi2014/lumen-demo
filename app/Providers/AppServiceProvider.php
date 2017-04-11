<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

class AppServiceProvider extends ServiceProvider
{
	public function boot()
	{
		//自动加载根目录下config文件夹下的配置文件
		foreach (Finder::create()->files()->name('*.php')->in($this->app->basePath('config')) as $file)
		{
			$filename = substr($file->getFileName(), 0, -4);
			if( !empty($filename) )
			{
				$this->app->configure($filename);
			}
		}
		
		//class_alias
// 		$arralias = config('app.aliases');
// 		if( !empty($arralias) )
// 		{
// 			foreach ($arralias as $alias => $original ) 
// 			{
// 				if( !class_exists($alias))
// 				{
// 					echo '-->' . 222;
// 					class_alias($original, $alias);
// 				}
// 			}
// 		}

		DB::listen(function ($query) {
			echo '<br/>';
			echo 'db:listen->start';
			var_dump($query->sql, $query->bindings, $query->time);
			echo 'db:listen->end';
		});
	}
	
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
//     	$this->app->singleton('Illuminate\Contracts\Routing\ResponseFactory', function ($app)
//     	{
//     		return new ResponseFactory($app['Illuminate\Contracts\View\Factory'], $app['Illuminate\Routing\Redirector']);
//     	});
    }
}

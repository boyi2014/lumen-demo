<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
	//多环境配置：域名配置
	$envHosts = [
			'local' => ['maimeng-blog.loc', 'maimeng-blog.appzd.net' ],
			'dev' => ['maimeng-blog.dev'],
			'test' => ['maimeng-blog.test'],
			'production' => ['maimeng-blog.com'],
	];
	
	$appEnvFile = getAppEnvFileBy( $envHosts );
    (new Dotenv\Dotenv(__DIR__.'/../', $appEnvFile))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

// $userAliases = [
// 		'Illuminate\Support\Facades\Log' => 'Log'
// 		'Illuminate\Support\Facades\Hash' => 'Hash',
// ];
// $app->withFacades(true, $userAliases);
$app->withFacades();

$app->withEloquent();


/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->middleware([
// 	Illuminate\Session\Middleware\StartSession::class,
// ]);

//每个Http请求都经过一个中间件
$app->middleware([
   //跨域访问
   'cors' => App\Http\Middleware\LumenCors::class, 
		
   // 根据 accept-language 设置语言（默认zh-CN）
   'locale' => App\Http\Middleware\ChangeLocale::class,
		
   App\Http\Middleware\StartSessionMiddleware::class
]);

//为路由指派中间件
$app->routeMiddleware([
	'jwt.auth' => App\Http\Middleware\Authenticate::class,
	'jwt.refresh' => App\Http\Middleware\RefreshToken::class,
]);


/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

$app->register(Illuminate\Redis\RedisServiceProvider::class);

// laravel常用命令行
$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);

//Laravel Eloquent 的缓存层。
// $app->register(App\Models\Providers\EloquentCacheProvider::class);
$app->register(MaiMeng\EloquentCache\Providers\EloquentCacheProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../routes/web.php';
    
    require __DIR__.'/../routes/api/v1.php';
});

return $app;

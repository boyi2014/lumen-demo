<?php 

if (!function_exists('getallheaders'))
{
	function getallheaders()
	{
		$headers = '';
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		} 
		return $headers;
	}
}

if( !function_exists('config_path') )
{
	function config_path( $path = '' )
	{
		return app()->basePath().'/config' . ($path ? '/'.$path : $path);
	}
}

function getAppEnvFileBy( $envHosts )
{
	if( isset($_SERVER['HTTP_HOST']) ) //http形式
	{
		$hostName = $_SERVER['HTTP_HOST'];
		if( empty($hostName) )
		{
			die('[error] don\'t support， because the http request has no hostname');
		}
		
		if( is_array($envHosts) || is_object($envHosts))
		{
			foreach ($envHosts as $env => $hosts)
			{
				if(in_array($hostName, $hosts))
				{
					$appEnv = $env;
					break;
				}
			}
		}
		
		if( empty($appEnv) || empty($envHosts[$appEnv]) )
		{
			die('[error] no environment');
		}
	}else //非http形式
	{
		$appEnv = env('APP_ENV');
		if( $appEnv == 'testing' ) //phpunit环境
		{
			$appEnv = 'test';
		}else  //php artisan等其他形式
		{
			$appEnv = 'local';
		}
	}
	
	return empty( $appEnv ) ? 'env/.env' : 'env/.env.'.$appEnv;
}


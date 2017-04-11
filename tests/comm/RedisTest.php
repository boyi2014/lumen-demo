<?php 

class RedisTest extends TestCase
{
	public function testConnect()
	{
		$servers = array(
//     				'tcp://127.0.0.1:6401',
//     				'tcp://127.0.0.1:6402',
//     				'tcp://127.0.0.1:6403',
//     				'tcp://127.0.0.1:6404',
//     				'tcp://127.0.0.1:6405',
//     				'tcp://127.0.0.1:6406',
					[
    					'host'     => env('REDIS_HOST', '127.0.0.1'),
    					'port'     => env('REDIS_PORT_01', 6401),
    					'database' => env('REDIS_DATABASE', 0),
    					'password' => env('REDIS_PASSWORD', null),
    				],
    				[
    					'host'     => env('REDIS_HOST', '127.0.0.1'),
    					'port'     => env('REDIS_PORT_02', 6402),
    					'database' => env('REDIS_DATABASE', 0),
    					'password' => env('REDIS_PASSWORD', null),
    				],
    				[
    					'host'     => env('REDIS_HOST', '127.0.0.1'),
    					'port'     => env('REDIS_PORT_03', 6403),
    					'database' => env('REDIS_DATABASE', 0),
    					'password' => env('REDIS_PASSWORD', null),
    				],
    				[
    					'host'     => env('REDIS_HOST', '127.0.0.1'),
    					'port'     => env('REDIS_PORT_04', 6404),
    					'database' => env('REDIS_DATABASE', 0),
    					'password' => env('REDIS_PASSWORD', null),
    				],
    				[
    					'host'     => env('REDIS_HOST', '127.0.0.1'),
    					'port'     => env('REDIS_PORT_05', 6405),
    					'database' => env('REDIS_DATABASE', 0),
    					'password' => env('REDIS_PASSWORD', null),
    				],
    				[
    					'host'     => env('REDIS_HOST', '127.0.0.1'),
    					'port'     => env('REDIS_PORT_06', 6406),
    					'database' => env('REDIS_DATABASE', 0),
    					'password' => env('REDIS_PASSWORD', null),
    				],
		);
		
		$options = array('cluster' => 'redis');
		
		$client = new Predis\Client($servers, $options);
		
		$key = 'test';
		$key_value = 'tank-002';
		$client->set($key, $key_value);
		
		var_dump($client->mget('test'));
		var_dump('delete:', $client->del('00534adc8e66027db4878a4b950315ba'));
		
		$result = $client->get($key);
		echo $result;
		$this->assertEquals($key_value, $result, '缓存设置失败');
	}
	
}

?>
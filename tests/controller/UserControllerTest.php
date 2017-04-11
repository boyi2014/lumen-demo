<?php 

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * @group controller
 * @author Administrator
 */
class UserControllerTest extends TestCase
{
	public function testGetUsers( )
	{
		echo trans('message.param.exception');
		
		echo Hash::make('aaaa');
		echo Carbon::now();
		
		Log::info("日志测试");
		Log::info('测试用例');
		 
		$this->get('/');
		
		$this->assertEquals(
			$this->app->version(), $this->response->getContent()
		);
	}
	
	
}

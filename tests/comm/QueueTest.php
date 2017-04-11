<?php

/**
 * @group queue
 * @author Administrator
 *
 */
class QueueTest extends TestCase
{
	public function testDispatchJob()
	{
		//消息队列
		$this->dispatch(new ExampleJob('JOB测试'));
		Log::info("日志测试");
		// 		$date = Carbon::now()->addSecond(3);
		// 		Queue::
	}
	
}
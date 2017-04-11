<?php 

use App\Services\ShortMsgService;
use App\Common\ShortMsgHelper;

class ShortMsgServiceTest extends TestCase
{
	protected $shortMsgService;
	
	public function setUp()
	{
		$this->shortMsgService = new ShortMsgService( new ShortMsgHelper() );
	}
	
	public function testSend()
	{
		$phone = '18516129903';
		$content = '短信测试';
		$sendStatus = $this->shortMsgService->send($phone, $content);
		echo '发送结果：' . $sendStatus;
		$this->assertTrue($sendStatus==0, '短信发送失败');
	}
}

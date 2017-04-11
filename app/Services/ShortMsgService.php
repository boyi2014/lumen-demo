<?php

namespace  App\Services;

use App\Common\ShortMsgHelper;

class ShortMsgService
{
	protected $shortMsgHelper;
	
	public function __construct( ShortMsgHelper $shortMsgHelper )
	{
		$this->shortMsgHelper = $shortMsgHelper;
	}
	
	public function send( $phone = '', $content = '' )
	{
		//TODO 扩展：加入队列
		return $this->shortMsgHelper->sendNote($phone, $content);
	}
}
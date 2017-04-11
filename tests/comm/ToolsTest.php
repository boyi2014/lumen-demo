<?php

use App\Common\Tools;

class ToolsTest extends TestCase
{
	public function testGetUrlContent()
	{
		$results = Tools::getUrlContent('http://pic.maimengjun.com/dfe6551802993baf893b3fdeffbfabf2_28596.jpg?imageInfo');
		$this->assertArrayHasKey('height', $results);
		$this->assertArrayHasKey('width', $results);
	}
	
	public function testGetPeriodTimeAt()
	{
		$timeType = 'day';
		$dateTime = time();
		$results = Tools::getPeroidTimeAt($timeType, $dateTime);
		var_dump($results);
	}
	
	public function testFormatDataTime()
	{
		$dateTime = time();
		$results = Tools::formatDataTime( $dateTime );
		$this->assertEquals('刚刚', $results);
	}
	
	public function testOthers()
	{
		//var_dump(getallheaders());
		$clientIP = Tools::getClientIP();
		$this->assertEquals('127.0.0.1', $clientIP);
		
		
	}
	
}
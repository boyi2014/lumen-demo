<?php

use App\Repositories\UserRepository;
use App\Models\User;

/**
 * @group repository
 * 分组测试
 */
class UserRepositoryTest extends TestCase
{
	private $userRepository;
	
	public function setUp()
	{
		$this->userRepository = new UserRepository( new User() );
	}
	
    public function testGetAgerLargenThan()
    {
    	$age = 10;
    	$results = $this->userRepository->getAgeLargerThan( $age );
    	
        $this->assertFalse($results->isEmpty(), '年龄>10的用户->不存在');
    }
    
}

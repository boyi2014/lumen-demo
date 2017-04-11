<?php 

use App\Services\FileService;
use App\Common\QiniuHelper;

class FileServiceTest extends TestCase
{
	protected $fileService;
	
	public function setUp()
	{
		parent::setUp();
		
// 		$this->app->bind(FileService::class, function () {
// 			return $this->fileService;
// 		});
		
		$this->fileService = new FileService( new QiniuHelper() );
	}
	
	/**
	 * @group failing
	 * 分组
	 */
	public function testGetImageInfo( )
	{
		$url = 'http://pic.maimengjun.com/qq.png';
		$results = $this->fileService->getImageInfo($url);
		
		$this->assertArrayHasKey('height', $results);
		$this->assertArrayHasKey('width', $results);
		$this->assertArrayHasKey('format', $results);
	}
	
	public function testGetQiniuUpToken()
	{
		$key = 'qq.png';
		$hash = 'FnA-Qsi8ik0mF2PrCZZ5zRjYwiko';
		$results = $this->fileService->getQiniuUpToken($key, $hash);
		
		$this->assertArrayHasKey('upToken', $results);
		$this->assertArrayHasKey('fileExist', $results['fileInQiniu']);
	}
	
	public function testUpload()
	{
		$fileName = 'E:/qq.png';
		$key = 'qq.png';
		$results = $this->fileService->upload($fileName, $key, true);
		
		$this->assertArrayHasKey('status', $results);
		$this->assertArrayHasKey('message', $results);
		$this->assertArrayHasKey('urlPreview', $results['data']);
	}
}

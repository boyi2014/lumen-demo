<?php 

use App\Common\Tools;
use App\Services\FileService;

class QiniuTest extends TestCase
{
	protected $fileService;
	
	public function setUp()
	{
		$this->fileService = new FileService();
	}
	
	public function testUploadFileToQiniu()
	{
		$fileName = 'qq.png';
		$localFile = 'E:/' . $fileName;
		Tools::uploadFileToQiniu($localFile, $fileName);
	}
	
// 	public function testRefreshQiniuCdn()
// 	{
// 		$urls = [
// 			'http://pic.maimengjun.com/qq.png'
// 		];
// 		Tools::refreshQiniuCdn($urls);
// 	}
	
// 	public function testGetQiniuFileStat()
// 	{
// 		$key = 'qq.png';
// 		Tools::getQiniuFileStat( $key );
// 	}

	public function testOthers()
	{
		$url = 'http://pic.maimengjun.com/8a5e0a369c53f24c4122fd927188cf25_24204.jpg';
		var_dump( Tools::getQiniuImageThumbnail($url, 50, 100) );
		
		$fileName = 'E:/qq.png';
		$key = 'qq.png';
		//echo md5_file( $fileName );
		$results = Tools::getQiniuFileStat( $key );
		var_dump($results);
		
		$results = Tools::getQiniuFileHash( $fileName );
		var_dump($results);
		
		$results = $this->fileService->upload($fileName, $key);
		var_dump($results);
		
		$results = $this->fileService->getQiniuUpToken( $key );
		var_dump($results);
	}
}

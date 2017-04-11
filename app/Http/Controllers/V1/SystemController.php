<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Common\BaseController;
use Illuminate\Http\Request;
use App\Services\FileService;

class SystemController extends BaseController
{
	protected $fileService;
	
	public function __construct(FileService $fileService)
	{
		$this->fileService = $fileService;
	}
	
	/**
	 * @api {GET} /getUpToken [获得文件上传upToken]
	 * @apiDescription 获得文件上传upToken
	 * @apiGroup System
	 * @apiPermission none
	 * @apiParam {String} fileName 文件名:png|jpeg|jpg
	 * @apiParam {String} hash 文件内容的Hash值，由七牛Hash算法提供
	 * @apiParam {String} [fileType] 文件类型
	 * @apiVersion 1.0.0
	 * @apiSuccess {integer} status [响应状态码：0 ]
	 * @apiSuccess {String} message [响应消息]
	 * @apiSuccess {json} data [响应数据]
	 * @apiSuccess {boolean} fileExist [文件是否存在，用于避免重复上传]
	 * @apiSuccess {String} upToken [七牛上传用的token]
	 * @apiSuccess {String} urlPreview [图片预览链接]
	 * @apiSuccess {String} urDownload [图片下载链接]
	 * @apiSuccess {json} fileInfo [图片信息：图片的长宽大小等信息]
	 * @apiSuccessExample {json} Success-Response
	 *  HTTP/1.1 200 文件已存在，并且内容相同，客户端不需要重复上传
     *  {
     *  	"status": 0,
     *     	"message": "", 
     *     	"data": 
     *     	{
     *     		"fileExist":"true",
     *     		"upToken":"8bLqz-KeWiZ1mVJKV2Eg6lak2u0NTxmtFUzUUH-A:I32qGKwSi9r5BmqnnHHtVstgNuI=:eyJzY29wZSI6InByZXR0eWltYWdlczpxcS5wbmciLCJkZWFkbGluZSI6MTQ5MDk0NjYxNSwidXBIb3N0cyI6WyJodHRwOlwvXC91cC5xaW5pdS5jb20iLCJodHRwOlwvXC91cGxvYWQucWluaXUuY29tIiwiLUggdXAucWluaXUuY29tIGh0dHA6XC9cLzE4My4xMzYuMTM5LjE2Il19",
     *     		"urlPreview":"http://pic.maimengjun.com/qq.png",
     *     		"urDownload":"http://pic.maimengjun.com/qq.png?e=1490946615&token=8bLqz-KeWiZ1mVJKV2Eg6lak2u0NTxmtFUzUUH-A:IHIwveSH4ghsHnjZku4dnJ4EUp8=",
     *     		"fileInfo":
     *     		{
     *     			"fsize":2089,
     *     			"hash":"FnA-Qsi8ik0mF2PrCZZ5zRjYwiko",
     *     			"mimeType":"image\/png",
     *     			"putTime":1.490874954757e+16,
     *     			"fwidth":47,
     *     			"fheight":47
     *     		}
     *     	}
     *   }
     * @apiError {Integer} status [响应状态码：1]
     * @apiError {String} message [错误消息]
     * @apiErrorExample {json} Error-Response
     *   HTTP/1.1 200 参数缺失
     *   {
     *   	"status": 1,
     *   	"message": "参数缺失：文件名必填" 
     *   }
	 */
	public function getUpToken(Request $request)
	{
		$this->validate($request, [
				'fileName' => 'bail|required|max:255',
				'hash'=>'bail|required|max:255'
		]);
		
		$fileName = $request->input('fileName');
		$fileHashInQiniu = $request->input('hash');
		//文件扩展名限制
		//$extension = substr($fileName, strrpos($fileName, '.'));
		$uploadTokenInfo = $this->fileService->getQiniuUpToken( $fileName, $fileHashInQiniu );
		if( empty($uploadTokenInfo) )
		{
			return $this->errorStr( trans('message.param.exception') );
		}
		
		$results = [ 'fileExist'=>false ];
		$fileInQiniu = $uploadTokenInfo['fileInQiniu'];
		$fileExist = $fileInQiniu['fileExist'];
		$results['fileExist'] = $fileExist;
		
		$results['upToken'] = $uploadTokenInfo['upToken'];
		
		$previewUrl = $uploadTokenInfo['urlPreview'];
		$results['urlPreview'] = $previewUrl;
		
		$results['urlDownload'] = $uploadTokenInfo['urlDownload'];
		
		$results['fileInfo'] = [];
		if( $fileExist )
		{
			$results['fileInfo'] = $fileInQiniu['fileInfo'];
			
			//计算宽高
			$imageInfo = $this->fileService->getImageInfo( $previewUrl );
			$results['fileInfo']['fwidth'] = $imageInfo['width'];
			$results['fileInfo']['fheight'] = $imageInfo['height'];
		}
		return $this->succeed( $results );
	}
	
	public function uploadFile(Request $request)
	{
		$fileName = 'E:/qq.png';
		$key = 'qq.png';
		//echo md5_file( $fileName );
// 		$results = Tools::getQiniuFileStat( $key );
// 		var_dump($results);
		
// 		$results = Tools::getQiniuFileHash( $fileName );
// 		var_dump($results);

		$results = $this->fileService->getImageInfo('http://pic.maimengjun.com/8a5e0a369c53f24c4122fd927188cf25_24204.jpg');
		var_dump($results);
		exit(0);
		$results = $this->fileService->upload($fileName, $key);
		return response($results);
		
// 		$results = $this->fileService->getQiniuUpToken( $key );
// 		var_dump($results);
	}
}
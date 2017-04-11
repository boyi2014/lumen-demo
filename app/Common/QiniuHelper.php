<?php

namespace App\Common;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Cdn\CdnManager;
use Qiniu\Storage\UploadManager;
use Qiniu\QEtag;
use Qiniu\Processing\ImageUrlBuilder;


class QiniuHelper
{
	private $access_key;
	private $secret_key;
	private $bucket;
	private $domain;
	
	private $_instance = null;
	
	public function __construct( $access_key = null, $secret_key = null, 
			$bucket = null, $domain = null )
	{
		$this->access_key = empty($access_key) ? config('qiniu.access_key') : $access_key;
		$this->secret_key = empty($secret_key) ? config('qiniu.secret_key') : $secret_key;
		$this->bucket = empty($bucket) ? config('qiniu.bucket') : $bucket;
		$this->domain = empty($domain) ? config('qiniu.domain') : $domain;
	}
	
	//////////////////////////////////////////
	public static function instance( )
	{
		if( empty($this->_instance) )
		{
			$this->_instance = new QiniuHelper();
		}
		return $this->_instance;
	}
	
	public static function newInstance( )
	{
		return new QiniuHelper();
	}
	
	//////////////////////////////////////////
	/** 
	 * 获取七牛-upToken
	 * @param string $key [保存的文件名]
	 * @return string 
	 */
	public function getQiniuUpToken( $key = null )
	{
		$auth = self::getQiniuAuth();
		
		$bucket = $this->bucket;
		return $auth->uploadToken($bucket, $key);
	}
	
	/**
	 * 刷新七牛cnd缓存
	 * @param array $urls [带刷新的文件列表,最多一次100个]
	 * @return bool [true-成功；false-失败 ]
	 */
	public function refreshQiniuCdn( $urls = [] )
	{
		if( empty($urls) )  return false;
		
		$auth = self::getQiniuAuth();
		$cdnManager = new CdnManager($auth);
		list($refreshResult, $refreshErr) = $cdnManager->refreshUrls($urls);
		
		$refreshFlag = false;
		if ($refreshErr != null) 
		{
		    //var_dump('刷新失败', $refreshErr);
		    $refreshFlag = false;
		} else 
		{
		    //var_dump($refreshResult);
			$refreshFlag = true;
		}
		return $refreshFlag;
	}
	
	/**
	 * 获取文件的状态信息
	 * @param string $key
	 * @return array 
	 * [
	 * 	  fileExist： false-不存在 | true-已存在，
	 *    fileInfo：文件存在时，带出文件的相关信息
	 * ]
	 */
	public function getQiniuFileStat( $key = null )
	{
		if( empty($key) ) return [];
		
 		$auth = self::getQiniuAuth();
		
		$bucket = $this->bucket;
		$bucketMgr = new BucketManager($auth);
		list($ret, $err) = $bucketMgr->stat($bucket, $key);
		
		$results = [];
		if ($err !== null) 
		{
			$results['fileExist'] = false;
		} else 
		{
			$results['fileExist'] = true;
			$results['fileInfo'] = $ret;
		}
		return $results;
	}
	
	
	/**
	 * 获得预览地址
	 * @param string $key
	 * @param string $domain
	 * @return string
	 */
	public function getQiniuPreviewUrl( $key=null, $domain=null )
	{
		$keyEsc = str_replace("%2F", "/", rawurlencode($key));
		$domain = empty($domain) ? $this->domain : $domain;
		return "http://$domain/$keyEsc";
	}
	
	/**
	 * 下载链接
	 * @param string $baseUrl
	 * @return NULL|string
	 */
	public function getQiniuDownloadUrl( $baseUrl=null )
	{
		if( empty($baseUrl) ) return null;
		
		$auth = self::getQiniuAuth();
		
		// 对链接进行签名
		$signedUrl = $auth->privateDownloadUrl($baseUrl);
		return $signedUrl;
	}
	
	/**
	 * 获得文件的Hash值。<采用七牛的hash算法>
	 * @param string $localFile [本地文件路径 |网址]
	 * @return NULL|string [文件的hash值]
	 */
	public function getQiniuFileHash( $localFile = null )
	{
		if( empty($localFile) ) return null;
		
		$hashInQiniu = null;
		//list($res, $err) = Etag::sum( $localFile );
		list($res, $err) = QEtag::getEtag( $localFile );
		if ($err != null) 
		{
	        //echo $err['message'] . "\n";
			$hashInQiniu = null;
	    }else 
	    {
	    	$hashInQiniu = $res;
	    }
	    return $hashInQiniu;
	}
	
	/**
	 * 缩略图链接拼接
	 * @param string $url  图片链接
	 * @param int $width   宽度  [可选]
	 * @param int $height  高度  [可选]
	 * @param int $quality 图片质量 [可选]
	 * 
	 */
	public function getQiniuImageThumbnail( $url, $width=0, $height=0, $quality=75 )
	{
		$imageUrlBuilder = new ImageUrlBuilder();
		$thumbLink = $imageUrlBuilder->thumbnail($url, 1, $width, $height, 
				null, null, $quality, null);
		return $thumbLink;
	}
	
	/**
	 * 删除文件 
	 * @param string $key
	 * @return boolean [true-删除成功； false-删除失败]
	 */
	public function deleteQiniuFile( $key = null )
	{
		$delFlag = false;
		if( !empty($key) )
		{
			$upToken = self::getQiniuUpToken( $key );
			
			$bucket = $this->bucket;
			$bucketMgr = new BucketManager($auth);
			$err = $bucketMgr->delete($bucket, $key);
			if ($err !== null) 
			{
				//var_dump($err);
				$delFlag = false;
			} else
			{
				$delFlag = true;
			}
		}
		return $delFlag;
	}
	
	public function uploadFileToQiniu( $localFile = null, $key = null )
	{
		$upToken = self::getQiniuUpToken( $key );
		
		$uploadMgr = new UploadManager();
		list($ret, $err) = $uploadMgr->putFile($upToken, $key, $localFile);
		
		$uploadStatus = false;
		if ($err !== null)
		{
			$uploadStatus = false;
		} else
		{
			$uploadStatus = true;
		}
		return $uploadStatus;
	}
	
	protected function getQiniuAuth()
	{
		$accessKey = $this->access_key;
		$secretKey = $this->secret_key;
		return new Auth($accessKey, $secretKey);
	}
	
}
<?php

namespace  App\Services;

use App\Common\QiniuHelper;
use App\Common\Tools;

class FileService
{
	protected $qiniuHelper;
	
	public function __construct( QiniuHelper $qiniuHelper )
	{
		$this->qiniuHelper = $qiniuHelper;
	}
	
	/**
	 * 上传本地文件到七牛
	 * @param string $localFile [本地文件]
	 * @param string $key [保存的文件名]
	 * @param boolean $refreshDns [是否需要刷新dns]
	 * @return array 
	 * {
	 * 		status: true|false，
	 * 	    message: 处理消息，
	 *      data: {
	 *      	urlPreview: 预览地址，
	 *          urlDownload：下载地址
	 *      }
	 * }
	 */
	public function upload( $localFile = null, $key = null, $needRefreshDns = false )
	{
		$results = ['status'=>false, 'message'=>trans('message.upload.failure')];
		
		$fileStatInQiniu = $this->qiniuHelper->getQiniuFileStat( $key );
		if( empty($fileStatInQiniu['fileExist']) )
		{
			$results['message'] = trans('message.file.notExist');
			$uploadStatus = $this->qiniuHelper->doUploadFileToQiniu($localFile, $key);
			if( ! $uploadStatus )
			{
				$results['status'] = false;
				$results['message'] = $results['message'] 
						.'->' .trans('message.upload.failure');
			}else
			{
				$results['status'] = true;
				$results['message'] = $results['message'] 
						.'->' .trans('message.upload.failure');
			}
		}else
		{
			$results['message'] = trans('message.file.exist');
				
			//比较文件内容是否相同
			$remoteHash = $fileStatInQiniu['fileInfo']['hash'];
			$localHash = $this->qiniuHelper->getQiniuFileHash( $localFile );
			if( $remoteHash == $localHash  )
			{
				$results['status'] = true;
				$results['message'] = $results['message'] 
						. '->' .trans('message.file.same') 
						.'->' .trans('message.upload.cancel');
				
				$previewUrl = $this->qiniuHelper->getQiniuPreviewUrl( $key );
				$results['data']['urlPreview'] = $previewUrl;
				$results['data']['urlDownload'] = $this->qiniuHelper->getQiniuDownloadUrl( $previewUrl );
			}else
			{
				$results['message'] = $results['message'] 
						.'->' .trans('message.file.different');
				$uploadStatus = $this->qiniuHelper->uploadFileToQiniu($localFile, $key);
				if( ! $uploadStatus )
				{
					$results['status'] = false;
					$results['message'] = $results['message'] 
							.'->' .trans('message.upload.failure');
				}else
				{
					$results['status'] = true;
					$results['message'] = $results['message'] 
							.'->' .trans('message.upload.success');
						
					$previewUrl = $this->qiniuHelper->getQiniuPreviewUrl( $key );
					$results['data']['urlPreview'] = $previewUrl;
					$results['data']['urlDownload'] = $this->qiniuHelper->getQiniuDownloadUrl( $previewUrl );
						
					//刷新DNS
					if( $needRefreshDns )
					{
						$refreshFlag = $this->qiniuHelper->refreshQiniuCdn( [ $previewUrl ] );
						if( $refreshFlag )
						{
							$results['message'] = $results['message'] 
									.'->' .trans('message.dns.refresh.success');
						}else
						{
							$results['message'] = $results['message'] 
									.'->' .trans('message.dns.refresh.failure');
						}
					}
				}
			}
		}
		return $results;
	}
	
	public function getQiniuUpToken( $key = null, $hash = null )
	{
		if( empty($key) || empty($hash) ) return null;
		
		$results = [];
		//获取uptoken
		$upToken = $this->qiniuHelper->getQiniuUpToken( $key );
		$results['upToken'] = $upToken;
		
		//预览地址
		$previewUrl = $this->qiniuHelper->getQiniuPreviewUrl( $key );
		$results['urlPreview'] = $previewUrl;
		
		//下载链接
		$downloadUrl = $this->qiniuHelper->getQiniuDownloadUrl( $previewUrl );
		$results['urlDownload'] = $downloadUrl;
		
		//文件状态
		$fileStatInQiniu = $this->qiniuHelper->getQiniuFileStat( $key );
		$results['fileInQiniu'] = $fileStatInQiniu;
		return $results;
	}
	
	/**
	 * 获得图片的信息（宽|高|大小）
	 * @param string $url
	 * @param string $needImgSize [是否需要统计图片的大小]
	 * @return NULL | 
	 * [
	 * 		width: 宽，
	 *      height: 高，
	 *      size: 大小
	 * ]
	 */
	public function getImageInfo( $url, $needImgSize=false )
	{
		if( empty($url) ) return null;
	
		$results = [];
		if( stristr($url, 'http://7xkbpd') || stristr($url, 'http://img') ||
			stristr($url, 'http://pic.maimengjun.com') )
		{
			$results = Tools::getUrlContent("{$url}?imageInfo");
		}else
		{
			list($width, $height) = getimagesize($value['images']);
			$results = array('width'=>$width, 'height'=>$height);
		}
	
		if( $needImgSize )
		{
			$results['size'] = $this->qiniuHelper->getRemoteFileSize( $url );
		}
		return $results;
	}
}
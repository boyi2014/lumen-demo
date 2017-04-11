<?php
namespace App\Common;

trait ToolsHttp
{
	/**
	 * 获得请求头信息
	 * @param string $key
	 * @return string|mixed
	 */
	public static function headers( $key  = ''  )
	{
		$headers = array_change_key_case(getallheaders());
		if (!empty($key)) return isset($headers[$key]) ? $headers[$key] : '' ;
		return $headers;
	}
	
	/**
	 * 发送请求
	 * @strUrl          string    抓取的URL地址
	 * @arrActionData   array     POST提交数据
	 * @arrActionHeader array     请求头
	 * @return          array 请求结果
	 */
	public static function getUrlContent($strUrl, $arrActionData=[], 
			$arrActionHeader=[], $isArr=true )
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $strUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//  返回内容
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);// 跟踪重定向
		if ( !empty($arrActionHeader) )
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $arrActionHeaderData);//  设定请求头
		}
	
		if (!empty($arrActionData)) {
			curl_setopt($ch, CURLOPT_POST, count($arrActionData));//  POST 提交
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arrActionData);//  提交数据
		}
	
		$strHttpUrlContent = curl_exec($ch);
		$status  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
	
		if(!$isArr)
		{
			return $strHttpUrlContent;
		}
	
		$arrResult = json_decode($strHttpUrlContent, true) ;
		return is_array($arrResult)   ?  $arrResult  : '' ;  //  返回小写的 相应数据
	}
	
	public static function getRemoteFileSize( $url )
	{
		ob_start();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	
		$okay = curl_exec($ch);
		curl_close($ch);
		$head = ob_get_contents(); 
		ob_end_clean();  
		$regex = '/Content-Length:\s([0-9].+?)\s/';
		$count = preg_match($regex, $head, $matches);
		if (isset($matches[1]))
		{
			$size = $matches[1];
		}
		else
		{
			$size = '0'; //unknown
		}
		//$last_mb = round($size/(1024*1024),3);
		//$last_kb = round($size/1024,3);
		return $size;
	}
	
	/**
	 * 获取客户端外网IP
	 * @return string [外网IP地址]
	 */
	public static function getClientIP( )
	{
		$onlineip = '';
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown'))
		{
			$onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
		{
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
		{
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
		{
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
		{
			$onlineip = getenv('REMOTE_ADDR');
		}
		
		if( preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches) )
		{
			$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
			unset($onlineipmatches);
		}
		return $onlineip;
	}
}
<?php

namespace App\Common;

class ShortMsgHelper
{
	private $url;
	private $account;
	private $password;
	private $sign;
	private $subcode;
	
	private $_instance = null;
	
	public function __construct( $url = null, $account = null, $password = null, 
			$sign = null, $subcode = null )
	{
		$this->url = empty($url) ? config('shortMsg.url') : $url;
		
		$this->account = empty($account) ? config('shortMsg.account') : $account;
		$this->password = empty($password) ? config('shortMsg.password') : $password;
		
		$this->sign = empty($sign) ? config('shortMsg.sign') : $sign;
		$this->subcode = empty($subcode) ? config('shortMsg.subcode') : $subcode;
	}
	
	
	//////////////////////////////////////////
	public static function instance( )
	{
		if( empty($this->_instance) )
		{
			$this->_instance = new ShortMsgHelper();
		}
		return $this->_instance;
	}
	
	public static function newInstance( )
	{
		return new ShortMsgHelper();
	}
	
	/////////////////////////////////////////
	/**
	 * 发送短信
	 * @param string $phone
	 * @param string $content
	 * @return Integer [1-发送成功; 0-发送失败]
	 */
	public function sendNote($phone = '', $content = '')
	{
		$sendState   = 0 ;
		if (strlen($phone) == 11 && !empty($content))
		{
			$strCurrentTime =  date('YmdHi',time());
			$ret = $this->sendSms ( $phone, $content, uniqid ( rand (), true ), $strCurrentTime );
			$ret = $this->getSmsReport ();
			if( !empty($ret) )
			{
				$arrResult = json_decode($ret, true) ;
				if( is_array($arrResult) && $arrResult['result'] === '0')
				{
					$sendState = 1;
				}else{
					//发送失败
					//var_dump($arrResult);
				}
			}
		}
		return $sendState;
	}
	
	/**
	 * 发送短信
	 * @param string $phones 手机号码,
	 * @param string $content 短信内容
	 * @param string $msgid 短信ID(唯一，UUID)，可空
	 * @param string $sendtime 短信发送时间，可空
	 *
	 */
	private function sendSms($phones, $content, $msgid, $sendtime) {
		// 发送数据包json格式：{"account":"8528","password":"e717ebfd5271ea4a98bd38653c01113d","msgid":"2c92825934837c4d0134837dcba00150","phones":"15711666132","content":"您好，您的手机验证码为：430237。","sign":"【8528】","subcode":"8528","sendtime":"201405051230"}
		$data = array ('account' => $this->account, 'password' => $this->password,
					'msgid' => $msgid,
					'phones' => $phones,
					'content' => $content,
					'sign' => $this->sign,
					'subcode' => $this->subcode,
					'sendtime' => $sendtime );
		return $this->http_post_json ( __FUNCTION__, $this->url . "/Submit", json_encode ( $data ) );
	}
	
	/**
	 * 获取短信状态报告
	 *
	 */
	private function getSmsReport() {
		// 获取短信状态报告数据包json格式：{"account":"8528","password":"e717ebfd5271ea4a98bd38653c01113d"}
		$data = array ('account' => $this->account, 'password' => $this->password );
		return $this->http_post_json ( __FUNCTION__, $this->url . "/Report", json_encode ( $data ) );
	}
	/**
	 * 获取手机回复的上行短信
	 *
	 */
	private function getSms() {
		// 获取上行数据包json格式：{"account":"8528","password":"e717ebfd5271ea4a98bd38653c01113d"}
		$data = array ('account' => $this->account, 'password' => $this->password );
		return $this->http_post_json ( __FUNCTION__, $this->url . "/Deliver", json_encode ( $data ) );
	}
	
	/**
	 * PHP发送Json对象数据, 发送HTTP请求
	 *
	 * @param string $url 请求地址
	 * @param array $data 发送数据
	 * @return String
	 */
	private function http_post_json($functionName, $url, $data) {
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_FRESH_CONNECT, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FORBID_REUSE, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen ( $data ) ) );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$ret = curl_exec ( $ch );
		//echo $functionName . " : Request Info : url: " . $url . " ,send data: " . $data . "  \n";
		//echo $functionName . " : Respnse Info : " . $ret . "  \n";
		curl_close ( $ch );
		return $ret;
	}
}
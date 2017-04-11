<?php

namespace App\Common;

trait ToolsLib
{
	/**
	 * 密码加密存储
	 * @param string $password
	 * @return string
	 */
	public static function encryptPassword( $password =  '' )
	{
		if($password == '') return '';
		$password = md5(strval(trim($password)));
		$md5Password = md5($password . '@' . mb_substr($password, 0, 12, 'utf8')  );
		return $md5Password;
	}
	
}
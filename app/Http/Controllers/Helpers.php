<?php

namespace App\Http\Controllers;

/**
 *  封装 响应数据：统一接口返回
 * @author Administrator
 */
trait Helpers 
{
	
	/**
	 * 直接返回错误json
	 * @param string $errorStr [错误信息]
	 * @param integer $code
	 * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
	 */
	protected function errorStr( $errorStr = '发现未知错误', $code  = 1 )
	{
		if( is_null($errorStr) )
		{
			//取默认错误消息
			$arrErrors = trans('error');
			if( isset($arrErrors[$code]) )
			{
				$errorStr = $arrErrors[$code];
			}
		}
		return response( $this->wrapResult(null, $code, $errorStr) );
	}
	
	/**
	 * 返回成功json
	 * @param array $data [响应数据]
	 * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
	 */
	protected function succeed( $data = [] )
	{
		return response( $this->wrapResult($data, 0) );
		
// 		return response( $this->wrapResult($data, 0) )
// 					->header('X-Header-One', 'Header Value-001')
// 					->header('X-Header-Two', 'Header Value-002')
// 					->header('Content-type', 'application/json; charset=utf-8');
	}
	
	
	/**
	 * 返回结果封装
	 * @param array $data
	 * @param number $status
	 * @param string $message
	 * @return mixed
	 */
	private function wrapResult( $data=[], $code=0, $message='' )
	{
		$results = [];
		//$results['params'] = $_REQUEST;
		$results['code'] = intval($code);
		$results['message'] = strval($message);
		if( !empty($data) )
		{
			$results['data'] = $this->dealNullValue( $data );
		}
		return $results;
	}
	
	private function dealNullValue( $data )
	{
		$result = null;
		if( is_array($data) || is_object($data) )
		{
			$temp = [];
			foreach ($data as $key => $value)
			{
				if( method_exists($value, 'attributesToArray') )
				{
					$value = $value->attributesToArray();
				}
				
				$temp[$key] = $this->dealNullValue( $value );
			}
			$result = $temp;
		}else
		{
			$result = is_null($data) ? '' : "{$data}";
		}
		return $result;
	}
}
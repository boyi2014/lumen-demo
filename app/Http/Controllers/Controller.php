<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Validation\Validator;

class Controller extends BaseController
{
	use Helpers;
	
	/**
	 * 自定义错误消息格式
	 * @param Validator $validator
	 */
	protected function formatValidationErrors( Validator $validator)
	{
		return $this->errorStr( $validator->errors()->first() );
	}
}

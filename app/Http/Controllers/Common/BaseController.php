<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
	// 返回错误的请求
	protected function errorBadRequest($validator)
	{
		$result = [];
		$messages = $validator->errors()->toArray();
	
		if ($messages) {
			foreach ($messages as $field => $errors) {
				foreach ($errors as $error) {
					$result[] = [
							'field' => $field,
							'code' => $error,
					];
				}
			}
		}
	
		throw new ValidationHttpException($result);
	}
}
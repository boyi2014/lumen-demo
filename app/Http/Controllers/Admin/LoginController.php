<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\BaseController;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;

class LoginController extends BaseController
{
	public function login(Request $request)
	{
		//var_dump( $request->url() );
		
		$this->validate($request, [
				'email' => 'bail|required|email|max:255',
				'password' => 'required',
		]);
		
		//通过user返回一个Token
		$credentials = $request->only('email', 'password');
		$user = User::where('email', $credentials['email'])->where('password', $credentials['password'])->first();
		$token = Auth::newToken($user);
		return $this->succeed( ['user'=>$user, 'token' => $token] );
	}
}
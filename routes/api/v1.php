<?php

$app->get('example/profile', ['uses'=>'ExampleController@showProfile']);
$app->get('example/{id}', function($id) {
	return 'Example '.$id;
});

$app->post('auth/login', 'Auth\AuthController@postLogin');
	
$app->group(['namespace' => 'V1' ], function () use ($app) {
	#路由正则匹配
	$app->get('user/{id:[A-Za-z]+}', ['uses'=>'UserController@showProfile', 'as'=>'profile']);
	$app->get('user/{id:[1-9]+}', ['uses'=>'UserController@show']);
	$app->get('getUsers', 'UserController@getUsers');
	
	
	$app->get('getUpToken', 'SystemController@getUpToken');
	$app->get('uploadFile', 'SystemController@uploadFile');
});


$app->group(['prefix' => 'admin'], function () use ($app) {
	$app->get('users', ['middleware' => ['jwt.auth', 'jwt.refresh'], function () {
		list($usec, $sec) = explode(" ", microtime());
		echo "111" . '<br/>';
		echo ((float)$usec + (float)$sec) . '<br/>';
		//var_dump( $request->user() );
		echo 111;

	}]);
});

// $app->get('login', ['uses'=>'Admin\LoginController@login', 'as'=>'login']);
$app->group(['prefix' => 'api'], function () use ($app) {
	$app->post('login', [ //'middleware' => ['auth'],
		'as'=>'login',
		'uses' => 'Admin\LoginController@login',
	]);
});

<?php
return [
		'defaults' => [
				'guard' => env('AUTH_GUARD', 'api'),
		],
		
		'guards' => [
				'api' => [
						'driver' => 'jwt', //这里必须是jwt,由JWTGuard驱动
						'provider'=>'users'
				],
		],
		
		'providers' => [
				'users' =>[
					'driver'=> 'eloquent',
					'model' => App\Models\User::class
				]
		],
];

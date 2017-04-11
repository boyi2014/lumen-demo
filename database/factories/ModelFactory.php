<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
function randDate()
{
	return \Carbon\Carbon::now()
	->subDays(rand(1, 100))
	->subHours(rand(1, 23))
	->subMinutes(rand(1, 60));
}

$factory->define(App\User::class, function (Faker\Generator $faker) {
	$createdAt = randDate();
    return [
        'username' => $faker->username,
    	'password' => app('hash')->make(str_random(10)),
        'email' => $faker->email,
    	'nick_name' => $faker->nick_name,
    	'sex' => $faker->sex,
    	'age' => $faker->age,
    	'created_at' => $faker->created_at,
    	'updated_at' => $faker->updated_at,
    ];
});

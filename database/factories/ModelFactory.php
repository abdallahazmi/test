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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->title,
        'parent_id' =>$faker->numberBetween(1,50),

    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text,
        'category_id' =>  factory(App\Category::class)->create()->id,
        'user_id' => factory(App\User::class)->create()->id,
        'image' => $faker->image(),

    ];
});
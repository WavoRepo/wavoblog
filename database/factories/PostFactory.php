<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Posts;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Posts::class, function (Faker $faker) {
    $title = $faker->sentence($nbWords = 6, $variableNbWords = true);
    return [
        'user_id' => mt_rand(1, 10),
        'post_title' => $title,
        'post_content' => $faker->paragraph($nbSentences = 4, $variableNbSentences = true),
        'post_slug' => Str::slug($title),
        'status' => 'Published',
    ];
});

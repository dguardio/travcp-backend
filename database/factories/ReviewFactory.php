<?php

use Faker\Generator as Faker;

$factory->define(\App\Review::class, function (Faker $faker) {
    $user = \App\User::inRandomOrder()->get()->first();
    $experience = \App\Experience::inRandomOrder()->get()->first();

    return [
        "user_id" => $user->id,
        "experience_id" => $experience->id,
        "review_body" => $faker->sentence,
        "rating" => $faker->numberBetween(1, 5)
    ];
});

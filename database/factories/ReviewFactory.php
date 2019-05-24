<?php

use Faker\Generator as Faker;

$factory->define(\App\Review::class, function (Faker $faker) {
    $user = \App\User::inRandomOrder()->get()->first();
    $experience = \App\Experience::inRandomOrder()->get()->first();

    $rating = $faker->numberBetween(1, 5);
    $experience->rating = ($rating + $experience->rating)/ ++$experience->rating_count;

    $experience->save();
    return [
        "user_id" => $user->id,
        "experience_id" => $experience->id,
        "review_body" => $faker->sentence,
        "rating" => $rating
    ];

});

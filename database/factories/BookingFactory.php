<?php

use Faker\Generator as Faker;

$factory->define(\App\Booking::class, function (Faker $faker) {
    $user = \App\User::inRandomOrder()->get()->first();
    $experience = \App\Experience::inRandomOrder()->get()->first();

    return [
        "merchant_id" => $user->id, // replace with random users
        "price" => $faker->numberBetween(1000, 50000),
        "currency" => "naira" || "pounds" || "dollars",
        "user_id" => $faker->numberBetween(25, 42),
        "start_date" => $faker->date(),
        "end_date" => $faker->date(),
        "quantity" => $faker->randomDigit,
        "food_menu_ids" => "[2, 3, 4]",
        "experience_id" => $experience->id
    ];
});

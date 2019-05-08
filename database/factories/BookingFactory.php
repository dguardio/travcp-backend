<?php

use Faker\Generator as Faker;

$factory->define(\App\Booking::class, function (Faker $faker) {
    return [
        "merchant_id" => $faker->numberBetween(1, 222), // replace with random users
        "price" => $faker->numberBetween(1000, 50000),
        "currency" => "naira" || "pounds" || "dollars",
        "user_id" => $faker->numberBetween(25, 42),
        "start_date" => $faker->date(),
        "end_date" => $faker->date(),
        "quantity" => $faker->randomDigit,
        "food_menu_ids" => "[2, 3, 4]",
        "experience_id" => 2
    ];
});

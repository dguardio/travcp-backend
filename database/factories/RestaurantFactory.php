<?php

use App\BookableType;
use Faker\Generator as Faker;

$factory->define(App\Restaurant::class, function (Faker $faker) {
    $restaurantBookable = BookableType::whereName('experience')->first();
    return [
        'merchant_id' => 1,
        'bookable_type_id' => $restaurantBookable->id,
        'name' => $faker->sentence,
        'location' => $faker->city,
        'description' => $faker->text,
        'price_from' => $faker->randomNumber(2),
        'price_to' => $faker->randomNumber(6),
        'extra_perks' => $faker->realText(10),
        'drink_types' => $faker->realText(10),
        'dining_options' => $faker->realText(10),
        'has_outdoor_sitting' => array_random([true, false]),
        'opening_and_closing_hours' => json_encode(
            array(
                "monday" => ["opening" => "0800", "closing" => "2000"],
                "tuesday" => ["opening" => "0800", "closing" => "2000"],
                "wednesday" => ["opening" => "0800", "closing" => "2000"],
                "thursday" => ["opening" => "0800", "closing" => "2000"],
                "friday" => ["opening" => "0900", "closing" => "2000"],
                "saturday" => ["opening" => "0800", "closing" => "2100"],
            )
        ),
        'cancellation_policy' => $faker->randomHtml(4, 6)
    ];
});

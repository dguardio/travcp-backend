<?php

use App\BookableType;
use App\ExperienceType;
use Faker\Generator as Faker;

$factory->define(App\Experience::class, function (Faker $faker) {
    $experiences_type = ExperienceType::inRandomOrder()->get()->first();

    return [
        'title' => $faker->sentence,
        'slug' => $faker->url,
        'merchant_id' => 1,
        'about_merchant' => $faker->sentence,
        'contact_email' =>  $faker->unique()->safeEmail,
        'location' => $faker->address,
        'city' => $faker->city,
        'state' => $faker->city,
        'offerings' => $faker->word . "," . $faker->word,
        'language' => $faker->languageCode,
        'description' => $faker->text,
        'naira_price' => $faker->randomNumber(5),
        'dollar_price' => $faker->randomNumber(3),
        'pounds_price' => $faker->randomNumber(3),
        'meetup_location' => $faker->streetAddress,
        'price_from' => $faker->randomNumber(3),
        'price_to' => $faker->randomNumber(5),
        'itenary' => $faker->realText(20),
        'extra_perks' => $faker->realText(20),
        'drink_types' => $faker->realText(20),
        'dining_options' => $faker->realText(20),
        'has_outdoor_sitting' => $faker->boolean,
        'opening_and_closing_hours' => $faker->time(),
        'cancellation_policy' =>  $faker->sentence,
        'tourist_expected_items' => $faker->realText(10),
        'number_admittable' => $faker->randomNumber(2),
        'experiences_type_id' => $experiences_type->id,
        'rating' => 0,
        'rating_count' => 0,
        'history' => $faker->sentence." ".$faker->sentence,
    ];
});

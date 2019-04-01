<?php

use App\Event;
use App\BookableType;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $experienceBookable = BookableType::whereName('event')->first();
    return [
        'merchant_id' => 1,
        'bookable_type_id' => $experienceBookable->id,
        'title' => $faker->sentence,
        // 'slug' => $faker->slug,
        'about_merchant' => $faker->text,
        'location' => $faker->address,
        'contact_email' => $faker->email,
        // 'language' => $faker->languageCode,
        'description' => $faker->text,
        // 'naira_price' => $faker->randomNumber(5),
        // 'dollar_price' => $faker->randomNumber(3),
        // 'pounds_price' => $faker->randomNumber(3),
        // 'meetup_location' => $faker->streetAddress,
        // 'itenary' => $faker->realText(20),
        // 'tourist_expected_items' => $faker->realText(10),
        // 'number_admittable' => $faker->randomNumber(2)
    ];
});

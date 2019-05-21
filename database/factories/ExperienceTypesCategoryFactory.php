<?php

use App\ExperienceType;
use Faker\Generator as Faker;

$factory->define(\App\ExperienceTypesCategory::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "experiences_type_id" => ExperienceType::inRandomOrder()->first()->id,
    ];
});

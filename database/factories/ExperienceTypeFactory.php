<?php

use Faker\Generator as Faker;

$factory->define(\App\ExperienceType::class, function (Faker $faker) {
    return [
        "name" => $faker->firstName
    ];
});

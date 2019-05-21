<?php

use App\FoodClassification;
use Faker\Generator as Faker;

$factory->define(FoodClassification::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence
    ];
});

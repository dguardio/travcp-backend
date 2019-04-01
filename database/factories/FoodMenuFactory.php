<?php

use App\FoodMenu;
use App\Restaurant;
use Faker\Generator as Faker;

$factory->define(FoodMenu::class, function (Faker $faker) {
    return [
        'restaurant_id' => Restaurant::inRandomOrder()->first()->id,
        'category_id' => rand(1, 9),
        'description' => $faker->text,
        'price' => $faker->randomFloat(2, 0, 50000)
    ];
});

<?php

use App\FoodMenu;
use App\Experience;
use App\ExperienceType;
use App\FoodClassification;
use Faker\Generator as Faker;
use App\ExperienceTypesCategory;

$factory->define(FoodMenu::class, function (Faker $faker) {
    $experience_type = ExperienceType::inRandomOrder()->first();

    return [
        'restaurant_id' => Experience::where('experiences_type_id', $experience_type->id)->inRandomOrder()->first()->id,
        'category_id' => FoodClassification::inRandomOrder()->first()->id,
        'description' => $faker->text,
        'price' => $faker->randomFloat(2, 0, 50000)
    ];
});

<?php

use App\Experience;
use App\ExperienceType;
use App\ExperienceTypesCategory;
use App\FoodMenu;
use Faker\Generator as Faker;

$factory->define(FoodMenu::class, function (Faker $faker) {
    $experience_type = ExperienceType::inRandomOrder()->first();

    return [
        'restaurant_id' => Experience::where('experiences_type_id', $experience_type->id)->inRandomOrder()->first()->id,
        'category_id' => ExperienceTypesCategory::where('experiences_type_id', $experience_type->id)->inRandomOrder()->first()->id,
        'description' => $faker->text,
        'price' => $faker->randomFloat(2, 0, 50000)
    ];
});

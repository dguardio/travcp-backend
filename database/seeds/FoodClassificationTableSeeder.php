<?php

use Illuminate\Database\Seeder;

class FoodClassificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\FoodClassification::class, 10)->create();
    }
}

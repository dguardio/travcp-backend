<?php

use Illuminate\Database\Seeder;

class FoodMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\FoodMenu::class, 50)->create();
    }
}

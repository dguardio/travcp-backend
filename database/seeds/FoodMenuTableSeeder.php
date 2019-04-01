<?php

use App\FoodMenu;
use Illuminate\Database\Seeder;

class FoodMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(FoodMenu::class, 20)->create();
    }
}

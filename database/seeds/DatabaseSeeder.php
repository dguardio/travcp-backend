<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ExperienceTypeSeeder::class,
            ExperienceTypesCategorySeeder::class,
            ExperiencesTableSeeder::class,
            BookingsSeeder::class,
            ReviewSeeder::class,
            FoodMenuSeeder::class
            // RestaurantsTableSeeder::class,
        ]);
    }
}

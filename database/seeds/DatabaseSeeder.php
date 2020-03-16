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
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            ExperienceTypeSeeder::class,
            ExperienceTypesCategorySeeder::class,
            ExperiencesTableSeeder::class,
            BookingsSeeder::class,
            ReviewSeeder::class,
            FoodClassificationTableSeeder::class,
            FoodMenuSeeder::class,
            // RestaurantsTableSeeder::class,
            MedalsSeeder::class
        ]);
    }
}

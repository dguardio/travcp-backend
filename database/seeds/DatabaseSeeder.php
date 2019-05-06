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
        // $this->call(UsersTableSeeder::class);
        $this->call([
            UsersTableSeeder::class,
            // BookableTypesTableSeeder::class,
//            ExperiencesTableSeeder::class,
            // EventsTableSeeder::class,
            // RestaurantsTableSeeder::class,
            // FoodMenuTableSeeder::class
        ]);
    }
}

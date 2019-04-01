<?php

use App\BookableType;
use Illuminate\Database\Seeder;

class BookableTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookableType::create(['name' => 'experience', 'description' => "Tours, events, places and so on"]);
        BookableType::create(['name' => 'event', 'description' => "Any kind of event"]);
        BookableType::create(['name' => 'restaurant', 'description' => "Find foods and cuisines of different types"]);
        
    }
}

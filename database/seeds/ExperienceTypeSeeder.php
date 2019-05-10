<?php

use Illuminate\Database\Seeder;

class ExperienceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ExperienceType::class, 5)->create();
    }
}

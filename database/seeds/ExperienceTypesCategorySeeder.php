<?php

use Illuminate\Database\Seeder;

class ExperienceTypesCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ExperienceTypesCategory::class, 20)->create();
    }
}

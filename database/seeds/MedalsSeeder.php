<?php

use App\Medal;
use Illuminate\Database\Seeder;

class MedalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medals = config('medals');
        foreach($medals as $medal) {
            $db_medal = Medal::whereName($medal['name'])
                ->first();
            if ( !$db_medal ) {
                Medal::create($medal);
            } else {
                $db_medal->fill($medal);
                $db_medal->save();
            }
        }
    }
}

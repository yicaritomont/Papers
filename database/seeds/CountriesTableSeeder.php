<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $countrys = array(
            array('id' => 1,'name' => 'Colombia'),
            array('id' => 2,'name' => 'Brasil'),
            array('id' => 3,'name' => 'Argentina'),
            array('id' => 4,'name' => 'Ecuador'),
        );

        foreach ($countrys as $country) {
            Country::create($country);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\City;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $c = new City();
        $c->countries_id = 1;
        $c->name = 'Ibague';
        $c->save();
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Citie;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $c = new Citie();
        $c->countries_id = 1;
        $c->name = 'Ibague';
        $c->save();
    }
}

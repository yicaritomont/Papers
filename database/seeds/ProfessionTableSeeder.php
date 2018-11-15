<?php

use Illuminate\Database\Seeder;
use App\Profession;

class ProfessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $professionS = [
			[
				'id'         => 1,
				'name'     => 'Contador',
			],
			[
				'id'         => 2,
				'name'     => 'Ingeniero ambiental',
			],
            
		];

		foreach ($professionS as $profession) {
			Profession::create($profession);
		}
    }
}

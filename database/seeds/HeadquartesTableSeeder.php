<?php

use Illuminate\Database\Seeder;
use App\Headquarters;

class HeadquartesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $headquartersS = [
			[
				'id'			=> 1,
				'client_id'		=> 1,
				'cities_id'		=> 1,
				'name'			=> 'Buenavista',
				'address'		=> 'Cra 4 # 6-3',
				'status'		=> 1,
				'slug'			=> md5(1),
			],
			[
				'id'			=> 2,
				'client_id'		=> 2,
				'cities_id'		=> 1,
				'name'			=> 'La esperanza',
				'address'		=> 'Cll 34 # 4-23',
				'status'		=> 1,
				'slug'			=> md5(2),
			],
		];

		foreach ($headquartersS as $headquarters) {
			Headquarters::create($headquarters);
		}
    }
}

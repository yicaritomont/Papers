<?php

use Illuminate\Database\Seeder;
use App\AppointmentLocation;

class AppointmentLocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $appointment_locations = array(
			array(
				'id'         => 1,
				'coordenada'     => '-122.34900 47.65124',
			),
			array(
				'id'         => 2,
				'coordenada'     => '-122.34900 47.65124)',
			),
			array(
				'id'         => 3,
				'coordenada'     => '-122.34900 47.65124)',
            ),
            array(
				'id'         => 4,
				'coordenada'     => '-122.34900 47.65124)',
            ),
             array(
				'id'         => 5,
				'coordenada'     => '-122.34900 47.65124)',
            ),
            
		);

		foreach ($appointment_locations as $appointment_location) {
			AppointmentLocation::create($appointment_location);
		}
	}
}

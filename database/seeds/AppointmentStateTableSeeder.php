<?php

use Illuminate\Database\Seeder;
use App\AppointmentState;

class AppointmentStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
       $appointment_stateS = array(
			array(
				'id'         => 1,
				'name'     => 'ACTIVO',
			),
			array(
				'id'         => 2,
				'name'     => 'INACTIVO',
			),
			array(
				'id'         => 3,
				'name'     => 'PENDIENTE',
            ),
            array(
				'id'         => 4,
				'name'     => 'EN PROCESO',
            ),
             array(
				'id'         => 5,
				'name'     => 'FINALIZADO',
            ),
            
		);

		foreach ($appointment_stateS as $appointment_state) {
			AppointmentState::create($appointment_state);
		}
	}
}
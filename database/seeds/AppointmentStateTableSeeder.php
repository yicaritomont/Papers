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
				'color'		=> 'success',
			),
			array(
				'id'         => 2,
				'name'     => 'CANCELADO',
				'color'		=> 'danger',
			),
			array(
				'id'         => 3,
				'name'     => 'PENDIENTE',
				'color'		=> 'warning',
            ),
            array(
				'id'         => 4,
				'name'     => 'EN PROCESO',
				'color'		=> 'info',
            ),
             array(
				'id'         => 5,
				'name'     => 'FINALIZADO',
				'color'		=> 'dark',
			),
			array(
				'id'         => 6,
				'name'     => 'REPROGRAMADO',
				'color'		=> 'purple',
            ),
            
		);

		foreach ($appointment_stateS as $appointment_state) {
			AppointmentState::create($appointment_state);
		}
	}
}
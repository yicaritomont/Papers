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
				'name'     => 'Solicitado',
				'color'		=> 'warning',
			),
			array(
				'id'         => 2,
				'name'     => 'Activo',
				'color'		=> 'success',
			),
			array(
				'id'         => 3,
				'name'     => 'En proceso',
				'color'		=> 'info',
            ),
            array(
				'id'         => 4,
				'name'     => 'Finalizado',
				'color'		=> 'dark',
            ),
             array(
				'id'         => 5,
				'name'     => 'Reprogramado',
				'color'		=> 'purple',
			),
			array(
				'id'         => 6,
				'name'     => 'Cancelado',
				'color'		=> 'danger',
            ),
            
		);

		foreach ($appointment_stateS as $appointment_state) {
			AppointmentState::create($appointment_state);
		}
	}
}
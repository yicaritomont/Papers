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
				'color'		=> '#f39c12e0',
			),
			array(
				'id'         => 2,
				'name'     => 'Activo',
				'color'		=> '#26b99ae0',
			),
			array(
				'id'         => 3,
				'name'     => 'En proceso',
				'color'		=> '#3498dbe0',
            ),
            array(
				'id'         => 4,
				'name'     => 'Finalizado',
				'color'		=> '#544948e0',
            ),
             array(
				'id'         => 5,
				'name'     => 'Reprogramado',
				'color'		=> '#2159f3',
			),
			array(
				'id'         => 6,
				'name'     => 'Cancelado',
				'color'		=> '#e74c3ce0',
            ),
            
		);

		foreach ($appointment_stateS as $appointment_state) {
			AppointmentState::create($appointment_state);
		}
	}
}
<?php

use Illuminate\Database\Seeder;
use Modulo;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Modulos = array(
			array(
				'id'       => 1,
                'name'     => 'Aplicación',
                'state' => 1
			)            
		);

		foreach ($Modulos as $Modulo) {
			Modulo::create($Modulo);
		}
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Modulo;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        //
        $Modulos = array(
			array(
				'id'       => 1,
                'name'     => 'Aplicación',
                'status' => 1
			)            
		);

		foreach ($Modulos as $Modulo) {
			Modulo::create($Modulo);
		}
    }
}

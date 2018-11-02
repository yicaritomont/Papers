<?php

use Illuminate\Database\Seeder;
use App\Menu;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        //
        $Menus = [
            ['id' => '1' , 'name' => 'Maestras' , 'url' => 'professions' ,'state' => 1 , 'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '2' , 'name' => 'Tipo Inspectores' , 'url' => 'inspectortypes','state' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '3' , 'name' => 'Tipos de Inspección' , 'url' => 'inspectiontypes','state' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '4' , 'name' => 'Subtipos de Inspección' , 'url' => 'inspectionsubtypes','state' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '5' , 'name' => 'Clientes' , 'url' => 'clients','state' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '6' , 'name' => 'Sedes' , 'url' => 'headquarters','state' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '7' , 'name' => 'Compañias' , 'url' => 'companies','state' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '8' , 'name' => 'Inspectores' , 'url' => 'inspectors','state' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '9' , 'name' => 'Agenda del inspector' , 'url' => 'inspectoragendas','state' => 1,'menu_id' => 8 , 'modulo_id' => 1],            
            ['id' => '10' , 'name' => 'Citas de Inspección' , 'url' => 'inspectionappointments','state' => 1,'menu_id' => 8 , 'modulo_id' => 1],

        ];

		foreach ($Menus as $Menu) {
			Menu::create($Menu);
		}
    }
}

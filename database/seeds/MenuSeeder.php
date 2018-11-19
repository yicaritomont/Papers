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
            ['id' => '1' , 'name' => 'Profesiones' , 'url' => 'professions' ,'status' => 1 , 'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '2' , 'name' => 'Tipo Inspectores' , 'url' => 'inspectortypes','status' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '3' , 'name' => 'Tipos de Inspección' , 'url' => 'inspectiontypes','status' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '4' , 'name' => 'Subtipos de Inspección' , 'url' => 'inspectionsubtypes','status' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '5' , 'name' => 'Clientes' , 'url' => 'clients','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '6' , 'name' => 'Sedes' , 'url' => 'headquarters','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '7' , 'name' => 'Compañias' , 'url' => 'companies','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '8' , 'name' => 'Inspectores' , 'url' => 'inspectors','status' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '9' , 'name' => 'Agenda del inspector' , 'url' => 'inspectoragendas','status' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '10' , 'name' => 'Citas de Inspección' , 'url' => 'inspectionappointments','status' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '11' , 'name' => 'Contratos' , 'url' => 'contracts','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '12' , 'name' => 'Formato expedición e inspección' , 'url' => 'formats', 'status' => 1 , 'menu_id' => 8, 'modulo_id' => 1],

        ];

		foreach ($Menus as $Menu) {
			Menu::create($Menu);
		}
    }
}

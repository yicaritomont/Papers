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
            ['id' => '1' , 'name' => 'Profession' , 'url' => 'professions' ,'status' => 1 , 'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '2' , 'name' => 'InspectorType' , 'url' => 'inspectortypes','status' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '3' , 'name' => 'InspectionType' , 'url' => 'inspectiontypes','status' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '4' , 'name' => 'InspectionSubtype' , 'url' => 'inspectionsubtypes','status' => 1,'menu_id' => 1 , 'modulo_id' => 1],
            ['id' => '5' , 'name' => 'Client' , 'url' => 'clients','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '6' , 'name' => 'Headquarters' , 'url' => 'headquarters','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '7' , 'name' => 'Company' , 'url' => 'companies','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '8' , 'name' => 'Inspector' , 'url' => 'inspectors','status' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '9' , 'name' => 'InspectorAgenda' , 'url' => 'inspectoragendas','status' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '10' , 'name' => 'Inspectionappointment' , 'url' => 'inspectionappointments','status' => 1,'menu_id' => 8 , 'modulo_id' => 1],
            ['id' => '11' , 'name' => 'Contract' , 'url' => 'contracts','status' => 1,'menu_id' => 5 , 'modulo_id' => 1],
            ['id' => '12' , 'name' => 'Formato expedición e inspección' , 'url' => 'formats', 'status' => 1 , 'menu_id' => 8, 'modulo_id' => 1],

        ];

		foreach ($Menus as $Menu) {
			Menu::create($Menu);
		}
    }
}

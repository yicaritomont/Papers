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
            ['id' => '1' , 'name' => 'Aplicación'                       ,'status' => 1 , 'menu_id' => 1 , 'order' => 1],
            ['id' => '2' , 'name' => 'Master'                           ,'status' => 1 , 'menu_id' => 1 , 'order' => 1],
            ['id' => '3' , 'name' => 'Cliente Menu'                     ,'status' => 1 , 'menu_id' => 1 , 'order' => 2],
            ['id' => '4' , 'name' => 'Inspección'                       ,'status' => 1 , 'menu_id' => 1 , 'order' => 3],
            ['id' => '5' , 'name' => 'Profession'                       ,'status' => 1 , 'menu_id' => 2 , 'order' => 1 , 'url' => 'professions'],
            ['id' => '6' , 'name' => 'InspectorType'                    ,'status' => 1 , 'menu_id' => 2 , 'order' => 2 , 'url' => 'inspectortypes'],
            ['id' => '7' , 'name' => 'InspectionType'                   ,'status' => 1 , 'menu_id' => 2 , 'order' => 3 , 'url' => 'inspectiontypes'],
            ['id' => '8' , 'name' => 'InspectionSubtype'                ,'status' => 1 , 'menu_id' => 2 , 'order' => 4 , 'url' => 'inspectionsubtypes'],
            ['id' => '9' , 'name' => 'Client'                           ,'status' => 1 , 'menu_id' => 3 , 'order' => 1 , 'url' => 'clients'],
            ['id' => '10' , 'name' => 'Headquarters'                    ,'status' => 1 , 'menu_id' => 3 , 'order' => 2 , 'url' => 'headquarters'],
            ['id' => '11' , 'name' => 'Company'                         ,'status' => 1 , 'menu_id' => 3 , 'order' => 3 , 'url' => 'companies'],
            ['id' => '12' , 'name' => 'Contract'                        ,'status' => 1 , 'menu_id' => 3 , 'order' => 4 , 'url' => 'contracts'],
            ['id' => '13' , 'name' => 'Inspector'                       ,'status' => 1 , 'menu_id' => 4 , 'order' => 1 , 'url' => 'inspectors'],
            ['id' => '14' , 'name' => 'InspectorAgenda'                 ,'status' => 1 , 'menu_id' => 4 , 'order' => 2 , 'url' => 'inspectoragendas'],
            ['id' => '15' , 'name' => 'Inspectionappointment'           ,'status' => 1 , 'menu_id' => 4 , 'order' => 3 , 'url' => 'inspectionappointments'],
            ['id' => '16' , 'name' => 'Formato expedición e inspección' ,'status' => 1 , 'menu_id' => 4 , 'order' => 4 , 'url' => 'formats'],

        ];

		foreach ($Menus as $Menu) {
			Menu::create($Menu);
		}
    }
}

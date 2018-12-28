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
        $Menus = [
            ['id' => '1' , 'name' => 'ManagementTools'                  ,'status' => 1 , 'menu_id' => 1, 'icon' => 'fa-cog'],
            ['id' => '2' , 'name' => 'Application'                      ,'status' => 1 , 'menu_id' => 2, 'icon' => 'fa-suitcase'],
            ['id' => '3' , 'name' => 'ManageUsers'                      ,'status' => 1 , 'menu_id' => 1, 'url' => 'users', 'icon' => 'fa-user'],
            ['id' => '4' , 'name' => 'ManagePermission'                 ,'status' => 1 , 'menu_id' => 1, 'url' => 'permissions', 'icon' => 'fa-wrench'],
            ['id' => '5' , 'name' => 'ManageRoles'                      ,'status' => 1 , 'menu_id' => 1, 'url' => 'roles', 'icon' => 'fa-lock'],
            ['id' => '6' , 'name' => 'ManageMenu'                       ,'status' => 1 , 'menu_id' => 1, 'url' => 'menus', 'icon' => 'fa-th-list'],
            ['id' => '7' , 'name' => 'Master'                           ,'status' => 1 , 'menu_id' => 2],
            ['id' => '8' , 'name' => 'ClientMenu'                       ,'status' => 1 , 'menu_id' => 2],
            ['id' => '9' , 'name' => 'Inspection'                       ,'status' => 1 , 'menu_id' => 2],
            ['id' => '10' , 'name' => 'Profession'                      ,'status' => 1 , 'menu_id' => 7 , 'url' => 'professions'],
            ['id' => '11' , 'name' => 'InspectorType'                   ,'status' => 1 , 'menu_id' => 7 , 'url' => 'inspectortypes'],
            ['id' => '12' , 'name' => 'InspectionType'                  ,'status' => 1 , 'menu_id' => 7 , 'url' => 'inspectiontypes'],
            ['id' => '13' , 'name' => 'InspectionSubtype'               ,'status' => 1 , 'menu_id' => 7 , 'url' => 'inspectionsubtypes'],
            ['id' => '14' , 'name' => 'Client'                          ,'status' => 1 , 'menu_id' => 8 , 'url' => 'clients'],
            ['id' => '15' , 'name' => 'Headquarters'                    ,'status' => 1 , 'menu_id' => 8 , 'url' => 'headquarters'],
            ['id' => '16' , 'name' => 'Company'                         ,'status' => 1 , 'menu_id' => 8 , 'url' => 'companies'],
            ['id' => '17' , 'name' => 'Contract'                        ,'status' => 1 , 'menu_id' => 8 , 'url' => 'contracts'],
            ['id' => '18' , 'name' => 'Inspector'                       ,'status' => 1 , 'menu_id' => 9 , 'url' => 'inspectors'],
            ['id' => '19' , 'name' => 'InspectorAgenda'                 ,'status' => 1 , 'menu_id' => 9 , 'url' => 'inspectoragendas'],
            ['id' => '20' , 'name' => 'Inspectionappointment'           ,'status' => 1 , 'menu_id' => 9 , 'url' => 'inspectionappointments'],
            ['id' => '21' , 'name' => 'Format'                          ,'status' => 1 , 'menu_id' => 9 , 'url' => 'formats'],
            ['id' => '22' , 'name' => 'Preformato'                      ,'status' => 1 , 'menu_id' => 7, 'url' => 'preformatos'],

        ];

		foreach ($Menus as $Menu) {
			Menu::create($Menu);
		}
    }
}

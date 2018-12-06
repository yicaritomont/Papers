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
            ['id' => '6' , 'name' => 'ManageModulo'                     ,'status' => 1 , 'menu_id' => 1, 'url' => 'modulos', 'icon' => 'fa-tasks'],
            ['id' => '7' , 'name' => 'ManageMenu'                       ,'status' => 1 , 'menu_id' => 1, 'url' => 'menus', 'icon' => 'fa-th-list'],
            ['id' => '8' , 'name' => 'Preformato'                       ,'status' => 1 , 'menu_id' => 1, 'url' => 'preformatos', 'icon' => 'fa-wpforms'],
            ['id' => '9' , 'name' => 'Master'                           ,'status' => 1 , 'menu_id' => 2],
            ['id' => '10' , 'name' => 'ClientMenu'                      ,'status' => 1 , 'menu_id' => 2],
            ['id' => '11' , 'name' => 'Inspection'                      ,'status' => 1 , 'menu_id' => 2],
            ['id' => '12' , 'name' => 'Profession'                      ,'status' => 1 , 'menu_id' => 9 , 'url' => 'professions'],
            ['id' => '13' , 'name' => 'InspectorType'                   ,'status' => 1 , 'menu_id' => 9 , 'url' => 'inspectortypes'],
            ['id' => '14' , 'name' => 'InspectionType'                  ,'status' => 1 , 'menu_id' => 9 , 'url' => 'inspectiontypes'],
            ['id' => '15' , 'name' => 'InspectionSubtype'               ,'status' => 1 , 'menu_id' => 9 , 'url' => 'inspectionsubtypes'],
            ['id' => '16' , 'name' => 'Client'                          ,'status' => 1 , 'menu_id' => 10 , 'url' => 'clients'],
            ['id' => '17' , 'name' => 'Headquarters'                    ,'status' => 1 , 'menu_id' => 10 , 'url' => 'headquarters'],
            ['id' => '18' , 'name' => 'Company'                         ,'status' => 1 , 'menu_id' => 10 , 'url' => 'companies'],
            ['id' => '19' , 'name' => 'Contract'                        ,'status' => 1 , 'menu_id' => 10 , 'url' => 'contracts'],
            ['id' => '20' , 'name' => 'Inspector'                       ,'status' => 1 , 'menu_id' => 11 , 'url' => 'inspectors'],
            ['id' => '21' , 'name' => 'InspectorAgenda'                 ,'status' => 1 , 'menu_id' => 11 , 'url' => 'inspectoragendas'],
            ['id' => '22' , 'name' => 'Inspectionappointment'           ,'status' => 1 , 'menu_id' => 11 , 'url' => 'inspectionappointments'],
            ['id' => '23' , 'name' => 'Format'                          ,'status' => 1 , 'menu_id' => 11 , 'url' => 'formats'],

        ];

		foreach ($Menus as $Menu) {
			Menu::create($Menu);
		}
    }
}

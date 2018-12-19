<?php

use App\User;
use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {
            // disable fk constrain check
            // \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Data cleared, starting from blank database.");

            // enable back fk constrain check
            // \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }


        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        $this->command->info('Default Permissions added.');

        // Set default Roles

        $roles_array = ['Admin','Inspector','Compania','Cliente','Agendador'];
        $input_roles = implode(",",$roles_array);

        // add roles
        foreach($roles_array as $role)
        {
            $role = Role::firstOrCreate(['name' => trim($role)]);

            if( $role->name == 'Admin' )
            {
                // assign all permissions
                $role->syncPermissions(Permission::all());
                $this->command->info('Admin granted all the permissions');
            }
            elseif( $role->name == 'Inspector')
            {
                // Assing inspector permissions
                $role->syncPermissions(Permission::where('name', 'view_inspectionappointments')->orWhere('name','LIKE','%_inspectoragendas')->get());
            }
            elseif( $role->name == "Compania")
            {
                // Assing compania permissions
                $role->syncPermissions(Permission::where('name' , 'LIKE' , '%_clients')->orWhere('name' , 'LIKE' , '%_inspectors')->orWhere('name' , 'LIKE' , '%_contracts')->get());
            }
            elseif( $role->name == 'Cliente')
            {
                 // Assing Cliente permissions
                 $role->syncPermissions(Permission::where('name', 'LIKE' ,'%_headquarters')->get());
            }
            elseif( $role->name == 'Agendador')
            {
                // Assing Agendador permissions
                $role->syncPermissions(Permission::where('name', 'LIKE' ,'%_inspectionappointments')->orWhere('name','LIKE' ,'%_inspectoragendas')->get());
            }

            // create one user for each role
            $this->createUser($role);
        }

        $this->command->info('Roles ' . $input_roles . ' added successfully');

        $this->command->info('corriendo semillas');
        $this->sembrarSemillas();

        // now lets seed some posts for demo
        factory(\App\Post::class, 30)->create();
        $this->command->info('Some Posts data seeded.');
        $this->command->warn('All done :)');
    }

    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {
        $user = factory(User::class)->create();
        $user->assignRole($role->name);

        if( $role->name == 'Admin' ) {
            $this->command->info('Here is your admin details to login:');
            $this->command->warn($user->email);
            $this->command->warn('Password is "secret"');
        }
    }

    private function sembrarSemillas()
    {
        AppointmentStateTableSeeder::run();
        AppointmentLocationTableSeeder::run();
        CountriesTableSeeder::run();
        CitiesTableSeeder::run();
        //ClientTableSeeder::run();
        //HeadquartesTableSeeder::run();
        //CompanyTableSeeder::run();
        ProfessionTableSeeder::run();
        InspectionTypeTableSeeder::run();
        InspectionSubTypeTableSeeder::run();
        InspectorTypeTableSeeder::run();
        ModuloSeeder::run();
        MenuSeeder::run();
        PreformatoSeeder::run();
        estiloCssSeeder::run();
        ChangePasswordDaysSeeder::run();
    }
}

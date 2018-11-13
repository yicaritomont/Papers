<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $companyS = [
			[
				'id'        => 1,
				'name'      => 'Electricos SA',
				'address'   => 'Cra 4 # 5-34',
				'phone'     => '243434634',
				'email'     => 'comercial@electricos.com',
				'status'    => 1,
				'activity'  => 'Comercialización de electrodomesticos',
				'slug'      => md5(1),
			],
			[
				'id'        => 2,
				'name'      => 'Colchones Amanecer',
				'address'   => 'Cll 46 # 9-74',
				'phone'     => '2363263326',
				'email'     => 'comercial@amanecer.com',
				'status'    => 1,
				'activity'  => 'Distribución de colchones',
				'slug'      => md5(2),
			],
           
		];

		foreach ($companyS as $company) {
			Company::create($company);
		}
    }
}

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
        $companies = [
			[
				'id'        => 1,
				'address'   => 'Cra 4 # 5-34',
				'phone'     => '243434634',
				'status'    => 1,
				'activity'  => 'Comercialización de electrodomesticos',
				'slug'      => md5(1),
				'user_id'	=> 6
			],
           
		];

		foreach ($companies as $company) {
			Company::create($company);
		}
    }
}

<?php

use Illuminate\Database\Seeder;
use App\InspectorType;

class InspectorTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $inspectorTypeS = [
			[
				'id'        				=> 1,
				'name'      				=> 'Científico',
				'inspection_subtypes_id'	=> 3
			],
			[
				'id'        				=> 2,
				'name'      				=> 'Electricista',
				'inspection_subtypes_id'	=> 1
			],
		];

		foreach ($inspectorTypeS as $inspectorType) {
			InspectorType::create($inspectorType);
		}
    }
}

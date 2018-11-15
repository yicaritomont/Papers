<?php

use Illuminate\Database\Seeder;
use App\InspectionSubtype;

class InspectionSubTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $inspectionSubTypeS = [
			[
				'id'                    => 1,
				'name'                  => 'Eléctrico',
				'inspection_type_id'    => 1,
			],
			[
				'id'                    => 2,
				'name'                  => 'Locativo',
				'inspection_type_id'    => 1,
            ],
            [
				'id'                    => 3,
				'name'                  => 'Locativo',
				'inspection_type_id'    => 2,
            ],
            [
				'id'                    => 4,
				'name'                  => 'Pisos',
				'inspection_type_id'    => 2,
			],
		];

		foreach ($inspectionSubTypeS as $inspectionSubType) {
			InspectionSubtype::create($inspectionSubType);
		}
    }
}

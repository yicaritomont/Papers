<?php

use Illuminate\Database\Seeder;
use App\InspectionType;

class InspectionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $inspectionTypeS = [
			[
				'id'        => 1,
				'name'      => 'Físico',
			],
			[
				'id'        => 2,
				'name'      => 'Químico',
			],
		];

		foreach ($inspectionTypeS as $inspectionType) {
			InspectionType::create($inspectionType);
		}
    }
}

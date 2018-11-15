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
				'id'        => 1,
				'name'      => 'Master',
			],
			[
				'id'        => 2,
				'name'      => 'Senior',
			],
		];

		foreach ($inspectorTypeS as $inspectorType) {
			InspectorType::create($inspectorType);
		}
    }
}

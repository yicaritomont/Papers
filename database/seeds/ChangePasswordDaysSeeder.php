<?php

use Illuminate\Database\Seeder;
use App\ChangePasswordDay;

class ChangePasswordDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        //
        $ChangePasswordDays = array(
			array(
				'id'       => 1,
                'days'     => '30',
                'state_id' => 1
			)            
		);

		foreach ($ChangePasswordDays as $ChangePassword) {
			ChangePasswordDay::create($ChangePassword);
		}
    }
}

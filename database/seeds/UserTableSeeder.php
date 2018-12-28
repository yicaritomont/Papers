<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $users = [
            ['name' => 'Compañía 1', 'email' => 'com@gmail.com', 'password' => bcrypt('secret'), 'remember_token' => str_random(10), 'picture' => 'images/user.png'],
        ];

		foreach ($users as $user) {
			User::create($user);
		}
    }
}

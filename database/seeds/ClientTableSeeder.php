<?php

use Illuminate\Database\Seeder;
use App\Client;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $clientS = [
			[
				'id'            => 1,
                'name'          => 'Juan',
                'lastname'      => 'Rodriguez Mendoza',
                'phone'         => '2676398',
                'email'         => 'juanM@mail.com',
                'cell_phone'    => '3165789458',
                'slug'          =>  md5(1),
			],
			[
				'id'            => 2,
                'name'          => 'Carlos Anres',
                'lastname'      => 'Amaya Matrinez',
                'phone'         => '2645455',
                'email'         => 'amaya@mail.com',
                'cell_phone'    => '312564343',
                'slug'          =>  md5(2),
			],
            
		];

		foreach ($clientS as $client) {
			Client::create($client);
		}
    }
}

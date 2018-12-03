<?php

use Illuminate\Database\Seeder;
use App\Estilo;

class estiloCssSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
      $estilos = array(
        array('id' => 1,
          'estilos' => '<style>
            @page {
              margin: 180px 50px;
            }
            #header {
              position: fixed;
              left: 0px;
              top: -180px;
              right: 0px;
              height: 150px;
              text-align: center;
            }
            #header .page:after {
              content: counter(page, decimal);
            }
            #footer {
              position: fixed;
              left: 0px;
              bottom: -180px;
              right: 0px;
              height: 80px;}
            #footer .page:after {
              content: counter(page, upper-roman); }
          </style>',
        ),
      );

      foreach ($estilos as $estilo) {
        Estilo::create($estilo);
      }
    }
}

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
          'estilos' =>
            '<style>
            @page {
              margin: 200px 50px 80px 50px;
              font-size: 12px;
            }
            #header {
              position: fixed;
              left: 0px;
              top: -180px;
              right: 0px;
              height: 150px;
              font-size: 11px;
              text-align: center;
            }
            #header .page:after {
              content: counter(page, decimal);
            }
            #header img {
              width:100px;
              height:100px;
            }
            #footer {
              position: fixed;
              left: 0px;
              bottom: -20px;
              right: 0px;
              height: 10px;
             }
             table>tbody>tr>td>textarea {
               width: 50px !important;
              }
              table{page-break-inside: avoid;}
          </style>',
        ),
      );

      foreach ($estilos as $estilo) {
        Estilo::create($estilo);
      }
    }
}

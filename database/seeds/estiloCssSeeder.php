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
          'name' => 'estilo_pdf',
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
              table {
                page-break-inside: avoid;
               }
              .contenedor_image {
                width: 600px;
                height: 700px;
               }
               .image {
                height: auto;
                max-width: 99%;
               }
          </style>',
        ),
        array('id' => 2,
          'name' => 'paginate_pdf',
          'estilos' =>'<script type="text/php">
            if (isset($pdf)) {
                $text = "page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
          </script>'),
      );

      foreach ($estilos as $estilo) {
        Estilo::create($estilo);
      }
    }
}

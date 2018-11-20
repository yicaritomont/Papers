<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WsdlFirmaController extends Controller
{
    //
    /*
	* Metodo que permite consumir el servicio de reportar acta estampillas
	* realizando las respectivas validaciones y reportando en PCI los codigos
	* de las estampillas
    */
    
    public function autenticarUsuario()
    {
        $parametros = [
            'Usuario'       =>  '3b8e04871b98b9ba7c8799753f7ae4f9',
            'Password'      =>  '177e2d984bfc26ebb46ed6fbe193b837',
            'Aplicacion'    =>  'WSfirma'
        ];
        $cliente = new ClienteSignaController();        
        $respuesta_servicio = $cliente->invocar('AutenticarUsuario',$parametros);

        echo '<pre>';
		echo 'respuesta del servicio:';
		print_r($respuesta_servicio);
		echo '</pre>';
    }
}

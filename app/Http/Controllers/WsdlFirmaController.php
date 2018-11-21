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
            'Usuario'       =>  'ACME_pruebas',
            'Password'      =>  'A0000usr78X',
            'Aplicacion'    =>  'WSfirma'
        ];
        $cliente = new ClienteSignaController('AutenticarUsuario',$parametros);
        echo '<pre>';
		echo 'respuesta del servicio:';
		print_r($cliente);
		echo '</pre>';
    }
}

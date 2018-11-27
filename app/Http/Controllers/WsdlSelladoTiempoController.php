<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WsdlSelladoTiempoController extends Controller
{
    //
    public function autenticarUsuario()
    {
        $parametros = [
            'Usuario'       =>  'ACME_pruebas',
            'Password'      =>  'A0000usr78X',
            'Aplicacion'    =>  'WSTSA'
        ];
        $urlServidor = "https://pre-wstsa.thsigne.com/WSTSA.asmx?wsdl";
        
        $cliente = new ClienteSignaController('AutenticaUsuario',$parametros,$urlServidor);
        echo '<pre>';
		echo 'respuesta del servicio:';
		print_r($cliente);
		echo '</pre>';
    }
}

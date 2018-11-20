<?php
/**
* ManejadorPeticionesController.php
*/
namespace App\Http\Controllers;
use App\Http\Controllers\HashUtilidades;
use App\Http\Controllers\SignaBlockController;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;



/**
 * Funciones para manejar las peticiones para signaBlock
 */
class ManejadorPeticionesController
{
    private $jsonError = ['response' => 'FAIL'];

    /**
     * Funcion para realizar solicitud del token de autorizacion
     */
    public function obtenerAuthToken()
    {
        $controller = new SignaBlockController;
        $response   = $controller->auth();
        if($response)
        {
            if($response->result == "OK")
            {
                return $response->data->token;
            }
        }
        return response()->json($this->jsonError);
    }

    /**
     * Funcion para realizar la solicitud de firma documentos
     */
    public function registrarDocumento($token,$documento)
    {
        $controller = new SignaBlockController;
        $response = $controller->documento($token,$documento);
        if($response)
        {
            if($response->result == "OK")
            {
                return $response->data->tx_hash;
            }
        }
        return response()->json($this->jsonError);
    }

    /**
     * Funcion para obtener la infomacion de la transaccion del documento firmado
     */
    public function documentoInfo($token,$documento)
    {
        $controller = new SignaBlockController;
        $response = $controller->documentoInfo($token,$documento);
        if($response)
        {
            if($response->result == "OK")
            {
                return $response->data;
            }
        }
        return response()->json($this->jsonError);
    }

    /**
     * funcion para obtener la el certificado de la transaccion del documetno firmado
     */
    public function documentoCertificado($token,$documento)
    {
        $controller = new SignaBlockController;
    }

}
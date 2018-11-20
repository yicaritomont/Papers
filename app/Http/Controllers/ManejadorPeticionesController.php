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
    public function documentoInfo($token,$document_hash)
    {
        $controller = new SignaBlockController;
        $response = $controller->documentoInfo($token,$document_hash);
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
    public function documentoCertificado($token,$document_hash)
    {
        $controller = new SignaBlockController;
        $response = $controller->documentoCertificado($token,$document_hash);
        if($response)
        {
            if($response->result == "OK")
            {
                return $response->data->file_base_64;
            }
        }
        return response()->json($this->jsonError);
    }

     /**
     * Funcion para realizar la solicitud de registro hash
     */
    public function hash($token,$hash)
    {
        $controller = new SignaBlockController;
        $response = $controller->hash($token,$hash);        
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
     * Funcion para obtener la infomacion de la transaccion del hash registrado
     */
    public function hashInfo($token,$hash)
    {
        $controller = new SignaBlockController;
        $response = $controller->hashInfo($token,$hash);
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
     * funcion para obtener la el certificado de la transaccion del hash generado
     */
    public function hashCertificado($token,$hash)
    {
        $controller = new SignaBlockController;
        $response = $controller->hashCertificado($token,$hash);
        if($response)
        {
            if($response->result == "OK")
            {
                return $response->data;
            }
        }
        return response()->json($this->jsonError);
    }

}
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
     * Funcion para realizat la solicitud 
     */

    

    /**
     * Funciones para manejar las peticiones para nodalblock
     */
    public function reportarDatos(string $datos)
    {
        $hash       = HashUtilidades::generarHash($datos);
        $controller = new NodalBlockController();
        $response   = $controller->reportarHash($hash);
        if($response)
        {
            return response()->json(json_decode($response->getBody()));
        }
        return response()->json($this->jsonError) ;
    }

    public function validarDatos(string $datos)
    {
        $hash       = HashUtilidades::generarHash($datos);
        $controller = new NodalBlockController();
        $response   = $controller->validarHash($hash);
        if($response)
        {
            return response()->json(json_decode($response->getBody()));
        }
        return response()->json($this->jsonError) ;
    }

    public function certificarDatos(string $datos)
    {
        $hash       = HashUtilidades::generarHash($datos);
        $controller = new NodalBlockController();
        $response   = $controller->certificarHash($hash);
        if($response)
        {
            return response()->json(json_decode($response->getBody()));
        }
        return response()->json($this->jsonError) ;
    }
}
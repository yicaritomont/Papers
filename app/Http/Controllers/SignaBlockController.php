<?php
/**
* SignaBlockController.php
*/
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class SignaBlockController
{
    private $client_id = "3b8e04871b98b9ba7c8799753f7ae4f9";
    private $api_key =  "177e2d984bfc26ebb46ed6fbe193b837";
    private $baseUrl = "https://pre-signeblockapi.thsigne.com/";

    private function crearCliente()
    {
        $cliente = new Client();
        return $cliente;
    }

    private function headers($authorization)
    {
        return [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'authorization' => $authorization,
                "Accept" => "application/json"
        ];
    }
    
    /**
     * Signa . Envio a endpoint auth(POST)
     * Envia como parametos el client_id y el api_key,
     * Retorna al TOKEN de acceso
     */
    public function auth()
    {        
        $client = $this->crearCliente();        
        try
        {
           $res = $client->request('POST', $this->baseUrl.'auth', [
            'form_params' => [
                'client_id' => $this->client_id,
                'api_key'   => $this->api_key
            ]
            ]);
            return json_decode($res->getBody());
        }
        catch (RequestException $e)
        {
           return false;
        }
    }

    /**
     * Signa. Envio a endpoint documento (POST)
     * Se registra y se custodia en la red signe
     * Requerido CABECERA authorization (TOKEN)
     * Envia como parametros file
     * Retorna tx_hash
     */
    public function documento($token,$documento)
    {
        $client = $this->crearCliente();
        $body = fopen($documento, 'r');
        try 
        {
            $res = $client->request('POST',$this->baseUrl.'documento',[
                'file' => $documento,
                'headers'   => $this->headers($token)
            ]);
                    
            return json_decode($res->getBody());
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }   



    /**
     * Signa Envio a endpoint documento/info (GET)
     * Recupera la informacion del documento
     * Requerido CABECERA authorization (TOKEN)
     * Envia como parametros el document_hash
     * Retorna informacion de la transaccion
     */

    public function documentoInfo($token,$document_hash)
    {
        $client = $this->crearCliente();
        try 
        {
            $res = $client->request('GET',$this->baseUrl.'documento/info/'.$document_hash,[
                //'document_hash' => $document_hash,
                'headers'   => $this->headers($token)
            ]);
            return json_decode($res->getBody());
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }

    /**
     * Signa Envio a endpoint documento/certificado (GET)
     * Genera certificado pdf con la informacion de la transaccion
     * Requerido CABECERA authorization (TOKEN)
     * Envia como parametos el document_hash
     * Retorna el base 64 del pdf
     */

    public function documentoCertificado($token,$document_hash)
    {
        $client = $this->crearCliente();
        try 
        {
            $res = $client->request('GET',$this->baseUrl.'documento/certificado/'.$document_hash,[
                //'document_hash' => $document_hash,
                'headers'   => $this->headers($token)
            ]);

            return json_decode($res->getBody());
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }

    /**
     * Signa Envio a endpont Hash (POST)
     * Registrar el hash recibido como parametro.
     * Requerido CABECERA authorization (TOKEN)
     * Envia como parametos el hash
     * Retorna tx_has de transaccióm
     */

    public function hash($token,$hash)
    {
        $client = $this->crearCliente();
        try 
        {
            $res = $client->request('POST',$this->baseUrl.'hash',[
                'form_params' => [
                    'hash' => $hash
                ],
                'headers'   => $this->headers($token)
            ]);
            return json_decode($res->getBody());
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }

    /**
     * Signa envia a enpoint hash/info (GET)
     * Recupera la informacion de la transaccion hash 
     * Requerido CABECERA authorization (TOKEN)
     * Envia como parametro HASH
     * Retorna la informacion  de la transaccion
     */

    public function hashInfo($token,$hash)
    {
        $client = $this->crearCliente();
        try 
        {
            $res = $client->request('GET',$this->baseUrl.'hash/info/'.$hash,[                
                //'hash' => $hash,                
                'headers'   => [
                    'authorization' => $token,
                ]
            ]);
            return json_decode($res->getBody());
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }

    /**
     * Signa Envio a endpoint hash/certificado (GET)
     * Genera certificado pdf con la informacion de la transaccion
     * Requerido CABECERA authorization (TOKEN)
     * Envia como parametos el hash
     * Retorna el base 64 del pdf
     */

    public function hashCertificado($token,$hash)
    {    
        $client = $this->crearCliente();
        try 
        {
            $res = $client->request('GET',$this->baseUrl.'hash/certificado/'.$hash,[
                //'hash' => $hash,
                'headers'   => [
                    'authorization' => $token,
                ]
            ]);
            return json_decode($res->getBody());
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }
}
?>
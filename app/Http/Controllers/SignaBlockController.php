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
        try {
            $res = $client->request('POST',$this->baseUrl.'documento',[
                'multipart' => [
                    'file' => $documento
                ],
                'headers'   => [
                    'auhorization' => $token
                ]
            ]);
        } 
        catch (RequestException $e) 
        {
            return false;
        }
    }   

}
?>
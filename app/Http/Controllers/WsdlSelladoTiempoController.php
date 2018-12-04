<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WsdlSelladoTiempoController extends Controller
{
    private $baseUrl    = "https://pre-wstsa.thsigne.com/WSTSA.asmx?wsdl";
    private $usuario    = 'ACME_pruebas';
    private $password   = 'A0000usr78X';
    
    /*********************************************************************************************
     *                           Start to private functions a Curl request
     *********************************************************************************************/

    /** Set a Headers to request */
    private function headers()
    {
        return [
        "Content-type: text/xml",
        "Accept: application/xml",
        "Cache-Control: no-cache",
        "Pragma: no-cache",
        ];
    }

    /** Initial Curl */
    private function initializer()
    {
        $curl = curl_init();
        return $curl;
    }

    public function autenticacionUsuario()
    {
        $curl = $this->initializer();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:web="http://webservice.thsigne.ae">
        <soapenv:Header/>
        <soapenv:Body>
        <web:AutenticaUsuario>
        <web:Usuario>'.$this->usuario.'</web:Usuario>
        <web:Password>'.$this->password.'</web:Password>
        <web:Aplicacion>WSTSA</web:Aplicacion>
        </web:AutenticaUsuario>
        </soapenv:Body>
        </soapenv:Envelope>',
        CURLOPT_HTTPHEADER => $this->headers()
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) 
        {
            echo "cURL Error #:" . $err;
        } 
        else
        {
            // Process data to send a clean information
            $dataResponse = [];
            $xmlArray = XmlArray::xml2array($response);
            if(count($xmlArray)>0)
            {
                foreach ($xmlArray as $content => $soap) 
                {
                    foreach($soap['soap:Body'] as $soapcontent => $body)
                    {
                        $dataResponse['ResultadoOperacion'] = $body['AutenticaUsuarioResult']['ResultadoOperacion']['Codigo']['value'];
                        if($body['AutenticaUsuarioResult']['ResultadoOperacion']['Codigo']['value'] == 0)
                        {
                            $dataResponse['Token'] = $body['AutenticaUsuarioResult']['Token']['value'];
                        }                    
                    }
                }    
            }
           
           return $dataResponse;
        }
    }

    public function selladoDocumento($token,$hash)
    {
        $curl = $this->initializer();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:web="http://webservice.thsigne.ae">
        <soapenv:Header/>
        <soapenv:Body>
        <web:SellaHash>
        <web:Token>'.$token.'</web:Token>
        <web:Hash>'.$hash.'</web:Hash>
        </web:SellaHash>
        </soapenv:Body>
        </soapenv:Envelope>',
        CURLOPT_HTTPHEADER => $this->headers()
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) 
        {
            echo "cURL Error #:" . $err;
        } 
        else
        {
            echo "<pre> RESPONSE SELLADO :: ";
            print_r($response);
            echo "</pre>";
            var_dump($response);
            exit();
            $dataResponse =[];
            $xmlArray = XmlArray::xml2array($response);
            if(count($xmlArray)>0)
            {
                foreach ($xmlArray as $content => $soap) 
                {
                    foreach($soap['soap:Body'] as $soapcontent => $body)
                    {
                        $dataResponse['ResultadoOperacion'] = $body['SellaDocumentoResult']['ResultadoOperacion']['Codigo']['value'];
                        if($body['SellaDocumentoResult']['ResultadoOperacion']['Codigo']['value'] == 0)
                        {
                            $dataResponse['IdentificadorSello'] = $body['SellaDocumentoResult']['IdentificadorSello']['value'];
                            $dataResponse['Sello'] = $body['SellaDocumentoResult']['Sello']['value'];

                        } 
                        
                    }
                }    
            }
            
            return $dataResponse;
        }
    }

    public function selladoHashDocumento()
    {

    }
}

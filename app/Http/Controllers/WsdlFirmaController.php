<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use SimpleXMLElement;


class WsdlFirmaController extends Controller
{
    private $baseUrl    = "https://pre-wsfirma.thsigne.com/WSFirma.asmx?wsdl";
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

    
    /** Create a Body Request */
    private function setBodyRequest($service,$params="",$tag = "",$aplication="")
    {
        // if  the request have a tag place after the param
        if($tag != "")
        {
            $tag = $tag.":";
        }

        $soap_request  = "<?xml version=\"1.0\"?>\n";
        $soap_request .= "<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" soap:encodingStyle=\"http://www.w3.org/2001/XMLSchema\">\n";
        $soap_request .= "  <soap:Body>\n";
        $soap_request .= "    <".$tag.$service." xmlns=\"http://webservice.thsigne.ae\">\n";
        
        /** Create a body xml request */
        foreach($params as $key => $value)
        {
            $soap_request .= "<".$tag.$key.">".$value."</".$tag.$key.">\n";
        }

        if($tag == "")
        {
            $soap_request .= "      <".$tag."Aplicacion>$aplication</".$tag."Aplicacion>\n";
        }

        $soap_request .= "    </".$tag.$service.">\n";
        $soap_request .= "  </soap:Body>\n";
        $soap_request .= "</soap:Envelope>";
        $bodyXml = $soap_request;

        return $bodyXml;
        
    }

    /**Define a setopt to send a curl request    */
    private function setSetoptCurl($parametros,$request,$tag="",$aplicacion)
    {        

        $PostField = $this->setBodyRequest($request,$parametros,$tag,$aplicacion);

        $setopt = array(
            CURLOPT_URL => $this->baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $PostField,
            CURLOPT_HTTPHEADER => $this->headers(),
        );

        return $setopt;
    }


    /********************************************************************************************
     *                         Functions initiating curl request
     ********************************************************************************************/

    // Method to request the user token for the SIGNATURE
    public function autenticacionUsuario()
    {
        // define the param for send a setopt
        $parametros = [
            'Usuario'       =>  $this->usuario,
            'Password'      =>  $this->password,
        ];

        $curl = $this->initializer();
        curl_setopt_array($curl, $this->setSetoptCurl($parametros,'AutenticarUsuario','','WSFirma'));

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
                        foreach ($body['AutenticarUsuarioResult'] as $tag => $resultautenticar) 
                        {
                            $dataResponse[$tag] = $resultautenticar['value'];                        
                        }                    
                    }
                }
            }
            return $dataResponse;            
        }
    }


    // Method to make the document's signature
    public function firmarDocumento($token,$base64Documento,$page,$positionX,$positionY,$width,$height)
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
        CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        \n<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
        \nxmlns:web=\"http://webservice.thsigne.ae\">
        \n<soapenv:Header/>
        \n<soapenv:Body>
        \n<web:FirmarDocumento>
        \n<web:Token>$token</web:Token>
        \n<web:TipoFirma>PAdES_LTV</web:TipoFirma>
        \n<web:SelloTiempo>true</web:SelloTiempo>
        \n<web:Documento>$base64Documento</web:Documento>
        \n<web:SelloFirma>\n<web:Pagina>$page</web:Pagina>
        \n<web:SelloX>$positionX</web:SelloX>
        \n<web:SelloY>$positionY</web:SelloY>
        \n<web:SelloWidth>$width</web:SelloWidth>
        \n<web:SelloHeight>$height</web:SelloHeight>
        \n</web:SelloFirma>
        \n</web:FirmarDocumento>
        \n</soapenv:Body>
        \n</soapenv:Envelope>",
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
                        
                        foreach ($body['FirmarDocumentoResult'] as $tag => $resultautenticar) 
                        {
                            $dataResponse[$tag] = $resultautenticar['value'];                        
                        }                    
                    }
                }
            }
    
            return $dataResponse;
        }
    }

    /*public function autenticarUsuario()
    {
        $parametros = [
            'Usuario'       =>  $this->usuario,
            'Password'      =>  $this->password,
            'Aplicacion'    =>  $this->aplicacion
        ];

        $urlServidor = "https://pre-wsfirma.thsigne.com/WSFirma.asmx?wsdl";
        
        $cliente = new ClienteSignaController('AutenticarUsuario',$parametros,$urlServidor);
        $cliente->soap_defencoding = 'UTF-8';
        $client->debug_flag = false;
    }*/
 
}

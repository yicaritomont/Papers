<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Meng\AsyncSoap\Guzzle\Factory;
use Illuminate\Foundation\Http\FormRequest;
use SimpleXMLElement;

class WsdlFirmaController extends Controller
{
    private $baseUrl    = "https://pre-wsfirma.thsigne.com/WSFirma.asmx?wsdl";
    private $usuario    = 'ACME_pruebas';
    private $password   = 'A0000usr78X';
    private $aplicacion = "WSfirma";
    
    public function autenticarUsuario()
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
        /*echo '<pre>';
		echo 'respuesta del servicio:';
		print_r($cliente);
		echo '</pre>';*/
    }


    // Headers to request 
    private function headers()
    {
        return [
            "Content-Type: text/xml",
            "cache-control: no-cache"
        ];
    }

    // init curl
    private function initializer()
    {
        $curl = curl_init();
        return $curl;
    }

    function array_to_xml(array $arr, SimpleXMLElement $xml)
    {
        foreach ($arr as $k => $v) {
            is_array($v)
                ? array_to_xml($v, $xml->addChild($k))
                : $xml->addChild($k, $v);
        }
        return $xml;
    }

    //create a body request 
    private function setBodyRequest($service,$params="",$tag = "")
    {
       
        $bodyXml = "<$service xmlns='http://webservice.thsigne.ae' > ";
        
            foreach($params as $key => $value)
            {
                $bodyXml .= "<$key>$value</$key>";
            }
        $bodyXml .= " </$service>";
        echo (string)$bodyXml;      

        //return $body;
        
    }

    private function setSetoptCurl()
    {
        $parametros = [
            'Usuario'       =>  $this->usuario,
            'Password'      =>  $this->password,
            'Aplicacion'    =>  $this->aplicacion
        ];
        $PostField = $this->setBodyRequest('AutenticarUsuario',$parametros);
        $setopt = array(
            CURLOPT_URL => $this->baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => (string)$PostField,
            CURLOPT_HTTPHEADER => $this->headers(),
        );
        return $setopt;
    }

    public function autenticacionUsuario()
    {
        
       /* $curl = $this->initializer();
        curl_setopt_array($curl, $this->setSetoptCurl());

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } 
        else 
        {
            return $response;
        }*/
    }


    public function firmarDocumento()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pre-wsfirma.thsigne.com/WSFirma.asmx",
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
                \n<web:Token>DD196A482C996A0D64DC0CA5289A45D4EA6B12FBD1CAF5F83A6F1DEE690067429EFA1D7800D1CDCCB5270B312343C7B8153CD82B665E75F4BD48A6A6C6F89A88</web:Token>\n<web:TipoFirma>PAdES_LTV</web:TipoFirma>\n<web:SelloTiempo>true</web:SelloTiempo>\n<web:Documento>JVBERi0xLjQKJeLjz9MKMSAwIG9iago8PC9UeXBlL1hPYmplY3QvU3VidHlwZS9JbWFnZS9XaWR0aCA0OS9IZWlnaHQgNDkvTGVuZ3RoIDQ4MC9Db2xvclNwYWNlL0RldmljZUdyYXkvQml0c1BlckNvbXBvbmVudCAxL0ZpbHRlci9DQ0lUVEZheERlY29kZS9EZWNvZGVQYXJtczw8L0sgLTEvQmxhY2tJczEgdHJ1ZS9Db2x1bW5zIDQ5L1Jvd3MgNDk+Pj4+c3RyZWFtCiagy/y/K8ocw5xyrKHN/I55HQIFEJBJhBWR8jn8qOwmUOEsXyo/+Npsoev/8MIE2gRHSwgV/+OEkEEggmE0gRT4/HSQQKkggsocIL6CCjxcRYi4tIctzxR3CNoIIj4QQs45V7YNBAqQIEkECpBMocJlD4ZdCI2kLKgYQlD4SRHZdBMjrRUUCBeggtrggXBBMJhMIIR6ioSYoWGgkEis2R0gQhFOiPhAggmCSZxyh+KRHRHSYtpBJA6S4YiyhwRHRHThBfKHCCGKwRtAkmkR2kkGMXwQVgkgghwRHhuCI6I6L3vSSCa2ggTDY0ECrFiyhwhTFOECYbCEcER8EohAihxFAgSKHKjQZToLzuCUILwih+Eky6I7wwYTaS9toJMJndoIEFsofhJJBAkCKHSKHKHCaGOyPpMrmCG4IocIjojqUPinLoIIIV7uF4YY+CI+2EggXSy8LHTFBggwkkgyh8OCBMIER8EUOER7WH9pJJDYQt3S4IJoEgiOiPuyh2kI8RDCCtIEgkeRiBZfi0nEj/CBUUOUPyOcEC0ECEIE4SBAo7XlR0gghTBAkNxFj/jtBBNFYiOyOyOiO//6yOkkCBCCBD+OFSY9DCRQ68cJIECBXYIodJKsRERERER/gAgAgAplbmRzdHJlYW0KZW5kb2JqCjMgMCBvYmoKPDwvVHlwZS9YT2JqZWN0L1N1YnR5cGUvSW1hZ2UvV2lkdGggMTYwL0hlaWdodCAzNC9GaWx0ZXIvRENURGVjb2RlL0NvbG9yU3BhY2UvRGV2aWNlUkdCL0JpdHNQZXJDb21wb25lbnQgOC9MZW5ndGggMjA5Nz4+c3RyZWFtCv/Y/+AAEEpGSUYAAQEBAGAAYAAA/9sAQwAKBwcIBwYKCAgICwoKCw4YEA4NDQ4dFRYRGCMfJSQiHyIhJis3LyYpNCkhIjBBMTQ5Oz4+PiUuRElDPEg3PT47/9sAQwEKCwsODQ4cEBAcOygiKDs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7/8AAEQgAIgCgAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A9TutXxO1np8JvLteGAOEi/327fTrVC6sriC8067u7x57l7tVO0lY0Uq2Qq/h1PNXvDigaLEQACzuzH1O88mjWf8AXaZ/1+r/AOgtXO05Q5n5HdFqFR04rum+r/r+rlGzsJp7nUbyzunt7pbx1yfmjcADAZf6jkVftdXP2hbPUYfsl03CgnMcv+43f6Hmk0X72pf9f0n8lpPEgB0SUkAlZIyp9DvXmklyw5kEmp1VTkuyT6r+v+GsatNeRIl3SOqL6scCnV5d44NovjmNvFMN5JoYtR9nEO7yxJ33Y/H36dq6ThPTw6lN4YbcZ3Z4pVZWUMpBB6EGuCso/DifD3Xf+Ebu5J7doJWdJJGJiOz7uG6D+fvWHeXVyvwz8K6ZbzvAmpTrBM6HB2bjkZ/H9KAPV0ljlyY5FfBwdpzilDqWKhgWHUZ5Feax6d4Y8N+MbOHRdfOn3cUqw3dnIskguN2MAnoCc9enSsvW9VvtB+KWp6xaRtLBaiL7YinrEyoD+uPocUAevB1LlAwLDqM8037RB/z2j/76FcJ4euoL74s6td20gkhn02J43HQgiOuH0A+CFtbk+JBdG7+0vt8rzMbO33eM5zQB7srBgGUgg9CDS1R0W0s7HRbS305WW0SIGEMSTtPIznnvV6gCI3EAbaZow2cY3DNSEgDJIA964vTZ/Dcdldf2lZRSzC7ufMY2DSE/vX/iCnPHvU6bj4LgbcTC99C1uGfcVhNypRScnkLjvx07UAddRWde6pNFeixsrI3dx5fmuDII1jUkgZY55JBwMdj0qMa/B/ZTXrQSiRZfINtwZPO3bdnXHXvnGOelAGrSAggEHIPQ1nRXd9NHPHeaabXERZXWZZFPt2IP4Y96paLqSxaZounwxNPPJZxPIFOBDHsHzMfc8AdTz6GgBml6tFa6Tb20UbXV2xcrBF1++3LHoo9zV62065nuY73VJg8sZzFBF/q4jjGfVm56n8qjfR5bCaS60ZkiaQ7pbaQfu5T7Hqp/T2qxZavDdSm2mRrW7A5gl4J91PRh7iuaCtaM/l2O6pK950uu/df5L0+/oR3OnXMFzJe6XMI5ZDulgk5jlPr6qfcVQ1bVorrSZ7WaNrW8Voy0EvU/OvKnow9xWpe6vDayi2hRrq7YfLbxdR7seij3NV49Ilvp47vWHSZ4zuit0H7uI+vqx9z+VE1e8YfPsFOVrTq9Nu7/AM16/f0NeuX13UPF1lqbjTtCtdT05kAUedtkDd85/wAK6iiuk4Tz7SfCmsRaP4mvLu1hgvdYiYRWUDDanytgZ6ZJNJP4O1a7+H2i2sSpBq2lOJkikYYLAn5cjj0r0KigDzxdH8SeKfEWm3msaPbaTbafL5zlHDvO4xgcduB1/WtGy8O3UnxA168vrMNpl/aLCrMwIk4UEYznsa7KigDz3wR4N1Hwx4x1F5Iy+nmApb3BcHcCykAjqCBn8qpeHLfxl4Zs7izi8KRXay3LzCSS5RTzgY6+3616fRQBS0u6uZtMt5dSt0srtkzLAJAwQ+me9WhJGTgOpP1rK1PwnoWs3f2vUdOjuJ9oTezMDgdBwfeobXwR4asbuK7ttJijnhYPG4ZsqR360AR6Xc3+l201rJol9MwuZ3V42i2sGkZhglwehHamDSL9fDcyGBRcy3ovBbI4wg84SbA3TOB16ZNdLRQBiO95a6odUTTbiaG7tkjkiQp5sLKWIyC2CDvPQ8Yqt/ZF8dMa58lRenUPt4ty4xwQNhbpnaOvTPtXSUUAZ0F5dXwmRtMntoxGcNOyZZvQBSePfNY2laDc6HZ6Zd2sckl2I0i1CN3BaVSAM56ZjPT/AGcjvXVUUAFY3iqNG0C4kZFLxDdGxHKH1B7Giisa/wDDZ04T+PH1E8JRoNAglCKJJRukfHLn1J71tUUUUP4aDF/x5BRRRWxzBRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQB//2QplbmRzdHJlYW0KZW5kb2JqCjUgMCBvYmoKPDwvTGVuZ3RoIDYwOC9GaWx0ZXIvRmxhdGVEZWNvZGU+PnN0cmVhbQp4nJVUy46bMBTd8xV32UpT4rdNdoGQSlU3o6aLWRrbJLSAJ4RU/e/+QG2StFFmkGZkIS72fZxz7jWHJN8mVIBCArY2QfAJM3SxiJiscps8JofpIfAlHHyONsgs5YDCmgymUs5BEpESDqaDRdPtEKw9PMYCeHLEQDIZfKXAsO2SxQYDDhXqBE3Hwy758HH7I3ztYs1DOGTpOZKQFBNgnKUihIdXxq5F8IsiVIHE7C0V7jjd5GACBL+gJC9yfGt2vatab36CccPY1I3RcDi55U3yu2RkNlnZwl4f96+HBi4CvYXLTUymUkWBq1mREaMriZWUrOCS5Gu1Iny1zssc55tizQtEuFiVGBdlsck2WUE3GeFrVHKSl6uNymahcvFOqEEZzmaVqU8OdoOutPXgemg1DM5COe7d4E4dWBe38tgGs9dNH31cC6EvoRVAUViEPFwiaxd8oNOD0VbDsx+i69G1rY95xsZ1z/6K8RYdndD9x2R858M4LjBeEIQVYLGkZMkRfN8W6TxJPEvyqz5zNM2fPkJxx9FNAwGRvvF2mq6A+cxjHHR/1CZ6vwYX3cFFv5WskMoybAUnxtma1lYrWwtuCaqookhSR6TQ2mSGmZpUGDOlpcSVroRA1TwppmZJPT1cmtH5zvXjpLHrmrEZzvz+3RrrH8A2sTU3xAJ9ODah6b9029jIfVLd+GFwZjx3j4bPvm6GLgjne3d8RQwm78QIIM7DMcMpTHD4xbx7ghmevWlP8KyH6dcQ4UbmGlp/BBfGcQxvH6iMpz7uRHW6aNzpA9fZvRs6ukTqBtJfe6NwtwplbmRzdHJlYW0KZW5kb2JqCjcgMCBvYmoKPDwvVHlwZS9QYWdlL01lZGlhQm94WzAgMCA1OTUgODQyXS9SZXNvdXJjZXM8PC9Gb250PDwvRjEgMiAwIFIvRjIgNCAwIFI+Pi9YT2JqZWN0PDwvaW1nMCAxIDAgUi9pbWcxIDMgMCBSPj4+Pi9Db250ZW50cyA1IDAgUi9QYXJlbnQgNiAwIFI+PgplbmRvYmoKMiAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2ZXRpY2EtQm9sZC9FbmNvZGluZy9XaW5BbnNpRW5jb2Rpbmc+PgplbmRvYmoKNCAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2ZXRpY2EvRW5jb2RpbmcvV2luQW5zaUVuY29kaW5nPj4KZW5kb2JqCjYgMCBvYmoKPDwvVHlwZS9QYWdlcy9Db3VudCAxL0tpZHNbNyAwIFJdPj4KZW5kb2JqCjggMCBvYmoKPDwvVHlwZS9DYXRhbG9nL1BhZ2VzIDYgMCBSPj4KZW5kb2JqCjkgMCBvYmoKPDwvUHJvZHVjZXIoaVRleHRTaGFycJIgNS41LjEzIKkyMDAwLTIwMTggaVRleHQgR3JvdXAgTlYgXChBR1BMLXZlcnNpb25cKSkvQ3JlYXRpb25EYXRlKEQ6MjAxODExMjIxNjMzMDgrMDEnMDAnKS9Nb2REYXRlKEQ6MjAxODExMjIxNjMzMDgrMDEnMDAnKS9UaXRsZShDZXJ0aWZpY2FkbyBkZSBibG9ja2NoYWluKS9DcmVhdG9yKFNpZ25lYmxvY2spPj4KZW5kb2JqCnhyZWYKMCAxMAowMDAwMDAwMDAwIDY1NTM1IGYgCjAwMDAwMDAwMTUgMDAwMDAgbiAKMDAwMDAwMzc4NiAwMDAwMCBuIAowMDAwMDAwNzA2IDAwMDAwIG4gCjAwMDAwMDM4NzkgMDAwMDAgbiAKMDAwMDAwMjk1NiAwMDAwMCBuIAowMDAwMDAzOTY3IDAwMDAwIG4gCjAwMDAwMDM2MzEgMDAwMDAgbiAKMDAwMDAwNDAxOCAwMDAwMCBuIAowMDAwMDA0MDYzIDAwMDAwIG4gCnRyYWlsZXIKPDwvU2l6ZSAxMC9Sb290IDggMCBSL0luZm8gOSAwIFIvSUQgWzxjNTRhYWYxMTZlNGVmZGYyM2M0ODlmOGVhZmU3NDI5Nz48YzU0YWFmMTE2ZTRlZmRmMjNjNDg5ZjhlYWZlNzQyOTc+XT4+CiVpVGV4dC01LjUuMTMKc3RhcnR4cmVmCjQyNzkKJSVFT0YK</web:Documento>
                \n<web:SelloFirma>
                \n<web:Pagina>1</web:Pagina>
                \n<web:SelloX>300</web:SelloX>
                \n<web:SelloY>400</web:SelloY>
                \n<web:SelloWidth>200</web:SelloWidth>
                \n<web:SelloHeight>100</web:SelloHeight>
                \n</web:SelloFirma>
            \n</web:FirmarDocumento>
        \n</soapenv:Body>
        \n</soapenv:Envelope>",
        CURLOPT_HTTPHEADER => array(
        "Content-Type: text/xml",
        "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
        }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use nusoap_client;

class ClienteSignaController extends Controller
{
   /*
    * Atributos Objeto
    * Instancia ClienteSigna
    */
    private $clienteSOAP;
    private $servicio;
    private $parametros;
    private $result;

    /*
    * Metodo Constructor
    */
    public function __construct($servicio = '', $parametros = array())
    {
        /*
        * Incializa el los atributos del cliente
        */      
        $this->inicializarCliente();     

        /*
        * Valida si se llamó el constructor con parametros
        */
        if($servicio != '')
        {
            $this->invocar($servicio, $parametros);
        }
        return $this;
    }   

    /*
    * Funcion de apoyo que asigna los atributos para la llamada
    * a los servicios
    */
    public function invocar($servicio = '', $parametros = array())
    {
        if($servicio != '')
        {
            /*
            * Valida si el servicio suministrado es un metodo del cliente
            */
            $bandAsignarParametros = false;
            if(method_exists($this, $servicio))
            {
                $this->servicio = $servicio;
                $bandAsignarParametros = true;
            }else
                {
                    echo '<h1>Servicio no disponible para el cliente</h1>';exit();
                }
    
            /*
            * Si llegan parametros se asignan al objeto
            */
            if($bandAsignarParametros && is_array($parametros))
            {
                $this->parametros = $this->limpiarParametros($parametros);

                /*
                * Realiza la llamada al servicio suministrado
                */
                return $this->llamarServicio();
            }else
                {
                    echo '<h1>Los parametros deben ser suministrados en un array no vacio</h1>';exit();
                }
        }else
            {
                echo '<h1>Debe suministrar un servicio</h1>';exit();
            }
    }

    /*
    * Funcion de apoyo que elimina los caracteres no permitidos encontrados
    * en los valores de los parametros
    */
    private function limpiarParametros($vecParametros)
    {
        foreach($vecParametros as $llaveParam => $valorParam)
        {
            if(is_array($valorParam))
            {
                $vecParametros[$llaveParam] = $this->limpiarParametros($valorParam);
            }else
                {
                    if(preg_match('/[^a-zA-Z\s0-9\.\,\#\-]+/', $valorParam))
                    {
                        /*
                        * Reemplaza los caracteres no permitidos encontrados
                        */
                        $valorParam = preg_replace('/[^a-zA-Z\s0-9\.\,\#\-]+/', '', $valorParam);
        
                        /*
                        * Asigna el nuevo valor al parametro
                        */
                        $vecParametros[$llaveParam] = $valorParam;
                    }
                }
        }

        return $vecParametros;
    }

    /*
    * Funcion de apoyo que establece los datos iniciales para crear el cliente
    */
    private function inicializarCliente()
    {
        /*
        * Crea el cliente para SOAP
        */
        
        $urlServidor = "https://pre-wsfrma.thsigne.com/WSFirma.asmx?wsdl";   
        

        $this->clienteSOAP = new nusoap_client($urlServidor, 'wsdl', '', '', '', ''); 
        $this->clienteSOAP->soap_defencoding = 'UTF-8';
        $this->clienteSOAP->response_timeout = -1;        
    }

    private function AutenticarUsuario()
    {

    }
    /*
    * Funcion de apoyo que realiza la llamada al servicio establecido para
    * la instancia del cliente
    */
    private function llamarServicio()
    {
        if($this->clienteSOAP->fault)
        {
            echo '<h2>Fault (Atencion - La peticion contiene un cuerpo SOAP no valido)</h2><pre>'; print_r($result); echo '</pre>';
        }
        
        $this->result = $this->clienteSOAP->call($this->servicio, $this->parametros, '', '', false, true);
        if($this->clienteSOAP->fault)
        {
            echo '<h2>Fault (Atencion - La peticion contiene un cuerpo SOAP no valido)</h2><pre>'; print_r($result); echo '</pre>';
        }else
        {
            $err = $this->clienteSOAP->getError();
            if($err)
            {
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
                
                echo '<h2>Request servicio</h2>';
                echo '<pre>' . htmlspecialchars($this->clienteSOAP->request, ENT_QUOTES) . '</pre>';
                echo '<h2>Response</h2>';
                echo '<pre>' . htmlspecialchars($this->clienteSOAP->response, ENT_QUOTES) . '</pre>';
                echo '<h2>Debug</h2>';
                echo '<pre>' . htmlspecialchars($this->clienteSOAP->debug_str, ENT_QUOTES) . '</pre>';
                echo '<h2>Error</h2>';
                echo '<pre>' . htmlspecialchars($this->clienteSOAP->getError(), ENT_QUOTES) . '</pre>';
                echo '<h2>Fault</h2>';
                echo '<pre>' . htmlspecialchars($this->clienteSOAP->fault, ENT_QUOTES) . '</pre>';
                
            }else
            {
                /*
                * Se solicita el tratamiento de la respuesta dependiendo
                * del servicio solicitado
                */
                $servicio = $this->servicio;
                //return $this->$servicio();
            }
        }
        return $this->result;
    }
}

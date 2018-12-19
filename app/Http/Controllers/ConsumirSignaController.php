<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\SignaFormat;
use Auth;

class ConsumirSignaController extends Controller
{
    //
    public function autenticarUsuarioWSFirma()
    {
        $response = [];
        if($_GET['info'])
        {
            $info = $_GET['info'];
            $usuario    = $info[0];
            $contrasena = $info[1];
            if($usuario != "")
            {
                if($contrasena != "")
                {
                    // Hace la solicitud para el token de la firma. WSFIRMA
                    $signaFirma = new WsdlFirmaController();
                    $tokenFirma = $signaFirma->autenticacionUsuario($usuario,$contrasena);     
        
                    if($tokenFirma['ResultadoAutenticacion'] == 0)
                    {
                        $response = ['error' => "", 'token' => $tokenFirma['Token']];
                    }
                    else
                    {
                        $response = ['error' => \Lang::get('words.SignaFailToken')];
                    }
                }
                else
                {
                    $response = ['error' => \Lang::get('words.SignaPassword')];
                }
            }
            else
            {
                $response = ['error' => \Lang::get('words.SignnaUser')];
            }
        }

        return $response;
       
    }

    /**
     * Funcion para la generacion de archivo inspeccion para envio a firma
     */
    public function firmarDocumentoWSFirma()
    {
        $response = [];
        /**token : response.token ,id_formato : id_formato */
        if($_GET['token'] != "")
        {
            if($_GET['id_formato'] != "")
            {
                // Genera el archivo con funcion del format controller
                $formatController = new FormatController();

                // Antes de generar el documento se verifica que el formato este firmado antes
                $verifica_firma = SignaFormat::where('id_formato',$_GET['id_formato'])->orderBy('created_at', 'desc')->limit(1)->get();
                if(count($verifica_firma) >0)
                {    
                    $page =1 ;
                    $positionX = '100';
                    $positionY = '800';
                    $base64Documento = HashUtilidades::obtenerContenidoTxt($verifica_firma[0]->base64);
                }
                else
                {
                    $documento = $formatController->downloadPDF($_GET['id_formato']);
                    $page =1 ;
                    $positionX = '300';
                    $positionY = '800';
                    Storage::put('Formato'.$_GET['id_formato'].'.pdf',$documento, 0777);
                    $base64Documento = HashUtilidades::generarBase64Documento(asset('../storage/app/Formato'.$_GET['id_formato'].'.pdf'));
                }
               
                // Se verifica las 2 firmas que debe de tener el documento
                $consultar_cantidad_firmas = SignaFormat::where('id_formato',$_GET['id_formato'])->get()->count();
                if($consultar_cantidad_firmas <= 2)
                {
                    // Se verifica que el documento no este firmado por el mismo usuario en session0
                    $consulta_firma = SignaFormat::where('id_usuario',Auth::user()->id)->where('id_formato',$_GET['id_formato'])->get();
                    if(count($consulta_firma)<=0)
                    {
                        //echo $base64Documento;
                        if($base64Documento != "")
                        {
                            //Envia a consumo a WSFIRMA 
                            $signaFirma = new WsdlFirmaController();
                            $respuestaFirma = $signaFirma->firmarDocumento($_GET['token'],$base64Documento,$page,$positionX,$positionY,'200','100');

                            if($respuestaFirma != "")
                            {     
                                if($respuestaFirma['IdFirma'] != 999)
                                {
                                    //generar el documento base64 y darle una rita. 
                                    //$doc_recibido = HashUtilidades::obtenerDocumentoBase64($respuestaFirma['DocumentoFirmado']);
                                    Storage::put('FormatoFirma'.$respuestaFirma['IdFirma'].'.txt',$respuestaFirma['DocumentoFirmado']);
                                    $ruta = 'storage/app/FormatoFirma'.$respuestaFirma['IdFirma'].'.txt';

                                    // como se tiene un identificador de firma, realizar el registro de la firma
                                    $signaFormat = new SignaFormat();
                                    $signaFormat->id_formato = $_GET['id_formato'];
                                    $signaFormat->id_usuario = Auth::user()->id;
                                    $signaFormat->id_firma   = $respuestaFirma['IdFirma'];
                                    $signaFormat->base64     = $ruta;
                                    $signaFormat->save();

                                    $response = ['error' => '' , 'respuestaFirma' => $respuestaFirma];            
                                }
                                else
                                {
                                    $response = ['error' => \Lang::get('words.SignaReturnError').' : '.$respuestaFirma['DocumentoFirmado']];
                                }
                            }
                            else
                            {
                                $response = ['error' => \Lang::get('words.SignaFailFile') ];
                            }
                        }
                        else
                        {
                            $response = ['error' => \Lang::get('words.SignaFailFile')];
                        }
                        
                    }
                    else
                    {
                        $response = ['error' => \Lang::get('words.SignaUserFail') ];
                    }

                }   
                else
                {                            
                    $response = ['error' => \Lang::get('words.FormatTwoSigna') ];
                }     
                
            }
            else
            {
                $response = ['error' => \Lang::get('words.SignaFailFile')];
            }
        }
        else
        {
            $response = ['error' => \Lang::get('words.SignaToken')];
        }

        return $response;
    }
}

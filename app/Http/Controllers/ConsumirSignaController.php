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
                $documento = $formatController->downloadPDF($_GET['id_formato']);
                Storage::put('Formato'.$_GET['id_formato'].'.pdf',$documento, 0777);
                $base64Documento = HashUtilidades::generarBase64Documento(asset('../storage/app/Formato'.$_GET['id_formato'].'.pdf'));
                if($base64Documento != "")
                {
                    //Envia a consumo a WSFIRMA 
                    $signaFirma = new WsdlFirmaController();
                    $respuestaFirma = $signaFirma->firmarDocumento($_GET['token'],$base64Documento,1,'300','800','200','100');

                    if($respuestaFirma != "")
                    {     
                        // Se verifica las 2 firmas que debe de tener el documento
                        $consultar_cantidad_firmas = SignaFormat::where('id_formato',$_GET['id_formato'])->get()->count();
                        if($consultar_cantidad_firmas <= 2)
                        {
                            // Se verifica que el documento no este firmado por el mismo usuario en session
                            $consulta_firma = SignaFormat::where('id_usuario',Auth::user()->id)->where('id_formato',$_GET['id_formato'])->first();
                            if(count($consulta_firma)<=0)
                            {
                                // como se tiene un identificador de firma, realizar el registro de la firma
                                $signaFormat = new SignaFormat();
                                $signaFormat->id_formato = $_GET['id_formato'];
                                $signaFormat->id_usuario = Auth::user()->id;
                                $signaFormat->id_firma   = $respuestaFirma['IdFirma'];
                                $signaFormat->base64     = $respuestaFirma['DocumentoFirmado'];
        
                                $signaFormat->save();
        
                                $response = ['error' => '' , 'respuestaFirma' => $respuestaFirma];
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

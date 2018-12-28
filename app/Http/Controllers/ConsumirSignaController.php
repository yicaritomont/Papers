<?php
/**
 * Contorlador que se encarga de realizar las peticiones para el consumo de los webservices de firma, sellado de firma y el registro de blockchain
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\SignaFormat;
use App\SelloFormat;
use App\BlockInfo;
use Auth;
use Session;

class ConsumirSignaController extends Controller
{
    /**
     * Función de apoyo que soliciuta el consumo de la autenticación para el usuario de webservice firma
     */
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
     * Funcion para solicitar la firma del documento al ws de firma
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

    /**
     * Función de apoyo que soliciuta el consumo de la autenticación para el usuario de webservice Sello
     */
    public function autenticarUsuarioWSSello()
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
                    $signaSelladoFirma = new WsdlSelladoTiempoController();
                    // Verifica que se tenga la variable token en session
                    if(!Session::has('TokenWSLSello'))        
                    {       
                        // solicita el token para el sellado de firma
                        $tokenSelladoFirma = $signaSelladoFirma->autenticacionUsuario($usuario,$contrasena);
                        if($tokenSelladoFirma['ResultadoOperacion'] == 0)
                        {
                            // se registra el token en una variable de session
                            Session::put('TokenWSLSello', $tokenSelladoFirma['Token']);
                            Session::save();

                            $response = ['error' => "", 'token' => $tokenSelladoFirma['Token']];
                        }
                        else
                        {
                            $response = ['error' => \Lang::get('words.SignaFailToken')];
                        }
                    }  

                    // Verifica el tiempo restante para el token
                    $token = session()->get('TokenWSLSello');
                    $consultaEstadoToken = $signaSelladoFirma->consultaEstadoToken($token);
                    if($consultaEstadoToken['Duracion']<=0)
                    {
                        //Solicita y asigna de nuevo el token
                        $tokenSelladoFirma = $signaSelladoFirma->autenticacionUsuario($usuario,$contrasena);
                        if($tokenSelladoFirma['ResultadoOperacion'] == 0)
                        {
                            // se registra el token en una variable de session
                            Session::put('TokenWSLSello', $tokenSelladoFirma['Token']);
                            Session::save();

                            $response = ['error' => "", 'token' => $tokenSelladoFirma['Token']];
                        }
                        else
                        {
                            $response = ['error' => \Lang::get('words.SignaFailToken')];
                        }
                    }
                    else
                    {
                        $response = ['error' => '', 'token' => $token];
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
     * Funcion para solicitar el sello del documento al ws de firma
     */
    public function sellarDocumentoWSSello()
    {
        $response = [];
        /**token : response.token ,id_formato : id_formato */
        if($_GET['token'] != "")
        {
            if($_GET['id_formato'] != "")
            {
                $verifica_firma = SignaFormat::where('id_formato',$_GET['id_formato'])->orderBy('created_at', 'desc')->limit(1)->get();
                if(count($verifica_firma)>0)
                {
                    $signaSelladoFirma = new WsdlSelladoTiempoController();
                    $base64Documento = HashUtilidades::obtenerContenidoTxt($verifica_firma[0]->base64);
                    $SelladoFirma = $signaSelladoFirma->selladoDocumento(Session::get('TokenWSLSello'),$base64Documento);
                    if($SelladoFirma['ResultadoOperacion'] == 0)
                    {
                        $signaFormat = new SelloFormat();
                        $signaFormat->id_formato = $_GET['id_formato'];
                        $signaFormat->id_usuario = Auth::user()->id;
                        $signaFormat->id_sello   = $SelladoFirma['IdentificadorSello'];
                        $signaFormat->sello     = $SelladoFirma['Sello'];
                        $signaFormat->save();
                        
                        // registra blockChain
                        $blockChain = $this->registrarBlockchain($_GET['id_formato']);

                        //como ya se tiene el sello
                        $response = ['error' =>'', 'response'=> $SelladoFirma];
                    }
                    else
                    {
                        // almacena el sello obtenid
                        $response = $SelladoFirma;
                    }
                }
                else
                {
                    $response = ['error' => 'Error!'];
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

    /**
     * Función envió de vista para la consulta de la cantidad de sellos. 
     */
    public function infoSignature()
    {
        return view('format.info_signature');
    }

    /**
     * Función para manejar la respuesta de la información de los consumos de sellos
     */
    public function consultaConsumo()
    {
        $response = [];
        /**token : response.token ,id_formato : id_formato */
        if($_GET['token'] != "")
        {
            $signaSelladoFirma = new WsdlSelladoTiempoController();
            $consultaSellado = $signaSelladoFirma->consumoConsulta(session()->get('TokenWSLSello'));
            $respuesta_completa = $consultaSellado;
            //print_r($consultaSellado);
            $retornar  = "<table class='table table-responsive'>";
            $retornar .= "<tr>
                            <td><b>".\Lang::get('words.SignaNombreUsuario')."</b></td>
                            <td>".$respuesta_completa['NombreUsuario']['value']."</td>
                          </tr>";
            $retornar .= "<tr>
                            <td><b>".\Lang::get('words.SignaNombreOrganizacion')."</b></td>
                            <td>".$respuesta_completa['NombreOrganizacion']['value']."</td>
                          </tr>";
            $retornar .= "<tr>
                            <td><b>".\Lang::get('words.SignaIdentificadorTSA')."</b></td>
                            <td>".$respuesta_completa['IdentificadorTSA']['value']."</td>
                          </tr>";
            $retornar .= "<tr>
                            <td><b>".\Lang::get('words.SignaTotalSellosConsumidos')."</b></td>
                            <td>".$respuesta_completa['TotalSellosConsumidos']['value']."</td>
                          </tr>";
            $retornar .= "<tr>
                            <td><b>".\Lang::get('words.SignaTotalSellosDisponibles')."</b></td>
                            <td>".$respuesta_completa['TotalSellosDisponibles']['value']."</td>
                          </tr>";
            $retornar .= "<tr>
                            <td><b>".\Lang::get('words.SignaFechaAlta')."</b></td>
                            <td>".$respuesta_completa['FechaAlta']['value']."</td>
                          </tr>";   
            $retornar .= "</table>";
            $response = ['error' => '', 'respuesta' => $retornar];
        }
        else
        {
            $response = ['error' => \Lang::get('words.SignaToken')];
        }

        return $response;
    }

    /**
     * funcion de apoyo que soliocita el registro de losdocumentos en la red blockchain
     */
    public function registrarBlockchain($format = "")
    {
        $return = 0;
        if($format == "")
        {
            $id_formato = $_GET['format'];
            $return = 1;
        }
        else
        {
            $id_formato = $format;
        }
        
        $response = [];
        // Solicita el token para el registro de block
        $signa = new ManejadorPeticionesController();
        $obtenerToken = $signa->obtenerAuthToken();
        if($obtenerToken != "")
        {
            // Solicita ubicación formato base64
            $verifica_firma = SignaFormat::where('id_formato',$id_formato)->orderBy('created_at', 'desc')->limit(1)->get();
            //obtiene el contenido base64
            $base64Documento = HashUtilidades::obtenerContenidoTxt($verifica_firma[0]->base64);
            //genera el hash apartir del documento
            $hash = HashUtilidades::generarHash($base64Documento.'15');
            //Registra el hash en la red de signa
            $registrar_hash = $signa->hash($obtenerToken,$hash);
            if(!is_object($registrar_hash))
            {
                // Almacena la información del block
                $blockFormat = new BlockInfo();
                $blockFormat->id_formato = $id_formato;
                $blockFormat->id_usuario = Auth::user()->id;
                $blockFormat->hash       = $hash;
                $blockFormat->base64     = $verifica_firma[0]->base64;
                $blockFormat->tx_hash    = $registrar_hash;
                $blockFormat->save();
                $response = ['error' => '' ,'respuesta' => $registrar_hash];

                if($return == 1)
                {
                    $alert = ['success', trans('words.BlockSuccess')];
                    return redirect()->route('formats.index')->with('alert',$alert);
                }
                else
                {
                    return $response;
                }

            }
            else
            {
                $alert = ['danger', trans('words.BlockFail')];
                $response = ['error' => \Lang::get('words.BlockFail')];
                return redirect()->route('formats.index')->with('alert',$alert);
            }
        }
        else
        {
            $alert = ['danger', trans('words.BlockFail')];
            $response = ['error' => \Lang::get('words.BlockFailToken')];
            return redirect()->route('formats.index')->with('alert',$alert);
        }
    }

    public function certificarBlockchain()
    {
        $id_formato = $_GET['format'];
        //consulta el hash certificado
        $consultaHash = BlockInfo::where('id_formato',$id_formato)->get();
        if($consultaHash)
        {
            // Solicitar el token de block
            $signa = new ManejadorPeticionesController();
            $obtenerToken = $signa->obtenerAuthToken();
            if($obtenerToken != "")
            {
                // Hacer llamado a certificado de block                
                $certificado_hash =(array) $signa->hashCertificado($obtenerToken,$consultaHash[0]->hash);               
                if(array_key_exists('file_base64',$certificado_hash))
                {
                    HashUtilidades::obtenerDocumentoBase64($certificado_hash['file_base64']);
                }
                else
                {
                    $alert = ['danger', trans('words.BlockMissing')];
                }
            }
            else
            {
                $alert = ['danger', trans('words.BlockMissing')];
            }

        }
        else
        {
            $alert = ['danger', trans('words.BlockMissing')];
        }
        return redirect()->route('formats.index')->with('alert',$alert);
    }
}

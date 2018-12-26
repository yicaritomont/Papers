<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Permission;
use App\Inspector;
use App\Profession;
use App\InspectorType;
use App\Company;
use App\Citie;
use App\Country;
use App\company_inspector;
use App\User;
use App\usuario_rol;
use App\Role;
use View;
use Illuminate\Http\Request;
use QR_Code\Types\QR_Url;
use QR_Code\Types\QR_meCard;
use QR_Code\QR_Code;
use QR_Code\Types\QR_CalendarEvent;
use Url;
use nusoap_client;
use Artisaninweb\SoapWrapper\SoapWrapper;
use SoapClient;
use Session;
use Illuminate\Support\Facades\Log;

class InspectorController extends Controller
{
    use Authorizable;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( !auth()->user()->hasRole('Admin') ){
            $request['id'] = Company::findOrFail(session()->get('Session_Company'))->slug;
        }
        
        if($request->get('id')){
            $companies = Company::with('user:id,name')->where('slug','=',$request->get('id'))->first();
            
            return view('inspector.index', compact('companies'));
        }
        return view('inspector.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$inspectors = Inspector::pluck('name', 'id');
        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');
        $countries = Country::pluck('name','id');
        $cities = Citie::pluck('name','id');
        $companies = Company::with('user')->get()->pluck('user.name', 'id');  

        return View::make('inspector.new', compact('professions','inspector_types','countries','cities','companies'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Si no es administrador agregue la compañia en sesión
        if( !auth()->user()->hasRole('Admin') ){
            $request['companies'] = session()->get('Session_Company');
        }

        if($request->id_inspector != "")
        {
            $this->validate($request, [
                'name'              => 'bail|required|min:2',
                'identification'    => 'required|numeric',
                'phone'             => 'required|numeric',
                'addres'            => 'required|string',
                'email'             => 'required|email|unique:users,email',
                'profession_id'     => 'required',
                'inspector_type_id' => 'required',
                'companies'         => 'required|min:1',
            ]);
        }
        else
        {
            $this->validate($request, [
                'name'              => 'bail|required|min:2',
                'identification'    => 'required|unique:inspectors|numeric',
                'phone'             => 'required|numeric',
                'addres'            => 'required|string',
                'email'             => 'required|email|unique:users,email',
                'profession_id'     => 'required',
                'inspector_type_id' => 'required',
                'companies'         => 'required|min:1',
            ]);
        }

        $request['roles'] = 2;

        // Verifica si se recibe un id de inspector
        if($request->id_inspector != "")
        {
            $inspector = Inspector::find($request->id_inspector);
            // Valida la relacion de la compañia con el inspector
            $validaRelacion = company_inspector::where('inspector_id',$inspector->id)->where('company_id',$request->companies)->get();
            if(count($validaRelacion)<=0)
            {
                $inspector->companies()->attach($request->companies);
                $userInspector = User::find($inspector->user_id);
                $userInspector->companies()->attach($request->companies);

                $alert = ['success', trans('words.RelationshipInspectorCompany')];
            }
            else
            {
                $alert = ['error', trans('words.InspectorCompany')];
            }

        }
        else
        {
            // Crea el usuario para el inspector
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt('secret');
            $user->picture = 'images/user.png';
            
            if($user->save())
            {
                // Consulta el identificador del inspector
                $id_inspector_registrado = Inspector::where('identification',$request->identification)->first();
               
                $user->assignRole('Inspector');
                UserController::syncPermissions($request, $user);
                $user->companies()->attach($request->companies);              
                
                $inspector = new Inspector();                
                $inspector->identification = $request->identification;
                $inspector->phone = $request->phone;
                $inspector->addres = $request->addres;                
                $inspector->profession_id = $request->profession_id;
                $inspector->inspector_type_id = $request->inspector_type_id;
                $inspector->user_id = $user->id;
              
                if ($inspector->save()) 
                {
                    $alert = ['success', trans_choice('words.Inspector', 1).' '.trans('words.HasAdded')];
                    $inspector->companies()->attach($request->companies);
                } 
                else 
                {
                    $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.Inspector', 1)];
                }
            }
            else
            {
                $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.Inspector', 1)];
            }
                    
        }
        return redirect()->route('inspectors.index')->with('alert', $alert);


    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\inspector  $inspector
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspector = Inspector::find($id);
        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');
        $countries = Country::pluck('name','id');
        $cities = Citie::pluck('name','id');
        $permissions = Permission::all('name', 'id');
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $user = $inspector->user;

        if(CompanyController::compareCompanySession($inspector->companies)){
            return view('inspector.edit', compact('inspector', 'permissions','professions','inspector_types','countries','cities', 'companies','user'));
        }else{
            abort(403, 'This action is unauthorized.');
        }
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inspector  $inspector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Get the inspector
        $inspector = Inspector::findOrFail($id);

        if( !CompanyController::compareCompanySession($inspector->companies) ){
            abort(403, 'This action is unauthorized.');        
        }

        //Si no es administrador agregue la compañia en sesión
        if( !auth()->user()->hasRole('Admin') ){
            $request['companies'] = session()->get('Session_Company');
        }

        $this->validate($request, [
            'name'              => 'bail|required|min:2',
            'identification'    => 'required|numeric|unique:inspectors,identification,'.$id,
            'phone'             => 'required|numeric',
            'addres'            => 'required|string',
            'email'             => 'required|email|unique:users,email,'.$inspector->user_id,
            'profession_id'     => 'required',
            'inspector_type_id' => 'required',
            'companies'         => 'required|min:1'
        ]);

        $request['roles'] = 2;
        
        $user = $inspector->user;
        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        UserController::syncPermissions($request, $user);
        $user->companies()->sync($request->companies);
        $inspector->companies()->sync($request->companies);
        
        $inspector->update([
            'identification'    => $request['identification'],
            'phone'             => $request['phone'],
            'addres'           => $request['addres'],
            'profession_id'     => $request['profession_id'],
            'inspector_type_id' => $request['inspector_type_id'],
            'user_id'           => $user->id,
        ]);

        $alert = ['success', trans_choice('words.Inspector', 1).' '.trans('words.HasUpdated')];

        return redirect()->route('inspectors.index')->with('alert', $alert);
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inspector = Inspector::findOrFail($id);

        if( !CompanyController::compareCompanySession($inspector->companies) ){
            abort(403, 'This action is unauthorized.');
        }

        // $this->authorize('validateCompany', $inspector->companies->first());

        if($inspector)
        {
		    switch ($inspector->status)
		    {
                case 1 :
                    $inspector->status = 0;
				    break;

                case 0 :
                    $inspector->status = 1;
				    break;

                default :
                    $inspector->status = 0;
			        break;
		    }

		    $inspector->save();
            $menssage = \Lang::get('validation.MessageCreated');
            echo json_encode([
                'status' => $menssage,
            ]);
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            echo json_encode([
                'status' => $menssage,
            ]);
        }

        //Antigua eliminación
       /*  if (Inspector::findOrFail($id)->delete()) {
            flash()->success(trans('words.Inspectors').' '.trans('words.HasEliminated'));
        } else {
            flash()->success(trans('words.Inspectors').' '.trans('words.NotDeleted'));
        }
        return redirect()->back(); */
    }

    /**
     *
    */
    public function VerifyInspector()
    {
        if($_GET['idInspector'] != "")
        {

            // search a inspector
            $inspector = Inspector::with('user')->where('identification',$_GET['idInspector'])->get();

            if(count($inspector)>0)
            {
                return json_encode($response = ['notificacion' => trans('words.InspectorExist') ,'data' => $inspector ]);
            }
            else
            {
                return json_encode($response = []);
            }

        }
    }

    /**
     * Funcion para consultar la infomacion del inspector tipo id card
     */
    public function IdCardInspector($id)
    {
        $infoInspector = Inspector::findOrFail($id);
        
        if( !CompanyController::compareCompanySession($infoInspector->companies) ){
            abort(403, 'This action is unauthorized.');        
        }

        // Se trae la información del usuario
        $usuario = User::find($infoInspector->user_id);
        $code = "";  

        /**
         * El bloque comentado a continuacion muestera como debe ser el consumo del WS de sellado del tiempo
         */
        /*
        $signaSelladoFirma = new WsdlSelladoTiempoController();

        // Verifica que se tenga la variable token en session
        if(!Session::has('TokenWSLSello'))        
        {       
            // solicita el token para el sellado de firma
            $tokenSelladoFirma = $signaSelladoFirma->autenticacionUsuario('ACME_pruebas','A0000usr78X');
            if($tokenSelladoFirma['ResultadoOperacion'] == 0)
            {
                // se registra el token en una variable de session
                Session::put('TokenWSLSello', $tokenSelladoFirma['Token']);
                Session::save();
            }
        }    

       
        // Verifica el tiempo restante para el token
        $token = session()->get('TokenWSLSello');
        $consultaEstadoToken = $signaSelladoFirma->consultaEstadoToken($token);     
        echo " Duracion ".$consultaEstadoToken['Duracion']."<br>";
        if($consultaEstadoToken['Duracion']<=0)
        {
            echo "solicita otro token y lo agrega a la temporal";
            //Solicita y asigna de nuevo el token
            $tokenSelladoFirma = $signaSelladoFirma->autenticacionUsuario('ACME_pruebas','A0000usr78X');
            Session::put('TokenWSLSello', $tokenSelladoFirma['Token']);
            Session::save();
        }
        
        if($token != "")
        {
            // Realice los llamados a metodos del ws.
            
            echo "TOKEN ->".$token;
            $base64File = HashUtilidades::generarBase64Documento('');            
            
            $SelladoFirma = $signaSelladoFirma->selladoDocumento($tokenSelladoFirma['Token'],$base64File);
            echo "<hr> SELLADO DE FIRMA";
            echo "<pre>";
            print_r($SelladoFirma);
            echo "</pre>";
           
            $hashdocumento = base64_encode(HashUtilidades::generarHashDocumento(''));
            echo "<hr>";
            echo $hashdocumento;
            echo "<br>";
            echo "FqG3Jo2Zv+UX8NbDv5brW0PW5R520XqjOI/uHA0VuNw=";
            $SelladoHashDocumento = $signaSelladoFirma->selladoHashDocumento($token,$hashdocumento);

            echo "<hr> SELLADO HASH DOCUMENTO";
            echo "<pre>";
            print_r($SelladoHashDocumento);
            echo "</pre>";

            $consultaSellado = $signaSelladoFirma->consumoConsulta(session()->get('TokenWSLSello'));
            echo "<hr>";
            echo "<pre>";
            print_r($consultaSellado);
            echo "</pre>";       
            
        }*/
        

        /**
         * El bloque  comentado a continuacion muestra como debe ser consumo del WS de firma 
         */

        /*$signaFirma = new WsdlFirmaController();
        $tokenFirma = $signaFirma->autenticacionUsuario('ACME_pruebas','A0000usr78X');      

        if($tokenFirma['ResultadoAutenticacion'] == 0)
        {
            // Por ahora se deja quemado una url de documento para hacer los test.
            
            // Solicita el paso del documento a base64
            $base64File = HashUtilidades::generarBase64Documento('');
          
            // Consume la forma del documento ,
            $respuestaFirma = $signaFirma->firmarDocumento($tokenFirma['Token'],$base64File,1,'300','800','200','100');

            echo "<pre>";
            echo print_r($respuestaFirma);
            echo "</pre>";
        }*/

        
        /**
         * El bloque comentado acontinuacion muestra como deben de realizar las peticiones para blokchain.
         */

        
        $concatenado = ObtenerConcatenadoObjeto::concatenar($infoInspector);
        $hash = HashUtilidades::generarHash($concatenado);
        $signa = new ManejadorPeticionesController();
        $obtenerToken = $signa->obtenerAuthToken();
        //$obtenerToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjNiOGUwNDg3MWI5OGI5YmE3Yzg3OTk3NTNmN2FlNGY5IiwibmJmIjoxNTQ0NTY3NjUyLCJleHAiOjE1NDQ1Njg1NTIsImlhdCI6MTU0NDU2NzY1MiwiaXNzIjoiU0lHTkVCTE9DSyIsImF1ZCI6IlNJR05FQkxPQ0tfQVBJIn0.sHz4aJSwuG4tWSBonjf7iVsgE6RUEUDNva9pWM0pU2s";

        echo "<pre>";
        print_r($obtenerToken);
        echo "</pre>";
        if($obtenerToken != "")
        {
            /*$docbase64 = HashUtilidades::generarBase64Documento('');
            $apiDocumento = $signa->apijsonDocumento($obtenerToken,$docbase64);
            echo "<pre>";
            print_r($apiDocumento);
            echo "</pre>";*/
            $sourcePath=asset('files/test.pdf');
            $registrar_documento = $signa->registrarDocumento($obtenerToken,$sourcePath);

            echo "<pre>";
            print_r($registrar_documento);
            echo "</pre>";
            //$registrar_documento = $signa->registrarDocumento($obtenerToken,asset('images/imagenes_user/cropper.jpg'));           
            
            echo $hash;

            echo "<br> REGISTRAR HASH";
            // Registrar hash 
            $registrar_hash = $signa->hash($obtenerToken,$hash);
            echo "<pre>";
            print_r($registrar_hash);
            echo "</pre>";
            
            echo "<br>INFORMACION HASH";
            // obtener informacion hash
            $informacion_hash = $signa->hashInfo($obtenerToken,$hash);
            echo "<pre>";
            print_r($informacion_hash);
            echo "</pre>";

            echo "<br> CERTIFICADO HASH";
            //obtener certificado de hash
            $certificado_hash = $signa->hashCertificado($obtenerToken,$hash);
            echo "<pre>";
            print_r($certificado_hash);
            echo "</pre>";
        }
        
        return view('inspector.card', compact('infoInspector','usuario'));

    }

    /**
     * funcion para validar la información del inspector al realizar lectura QR
     */
    public static function qrInfoInspector($id)
    {
        $url = new QR_Url($_SERVER["HTTP_HOST"].'/roles-permissions/public/ReadQrInspector/'.$id);
        $url->setSize(4)->setMargin(2)->svg();
    }

    /**
	 * Resolves the ajax requests
	 *
	 * @param  $_GET
	 * @return Response
	 */
    public function asincronia()
    {
        if(isset($_GET['country']))
        {
            $id = $_GET['country'];
            $citiesCountry = Citie::where('country','countries_id',$id);
            $citiesCountry[''] = 'Seleccione..';
            json_encode($response = ['citiesCountry'=>$citiesCountry]);
        }
        return $response;
    }

    /**
	 * Seleccionar los contratos en base a la compañia del inspector seleccionado
	 */
    public function contracts($id = null)
    {
        $contratos = [];

        if($id)
        {
            foreach(Inspector::find($id)->companies as $company)
            {
                $contratos = array_merge($contratos, $company->contracts->toArray());
            }
        }

        array_unshift($contratos, ['id' => '', 'name' => trans('words.ChooseOption')]);

        echo json_encode([
            'status' => $contratos
        ]);
    }

    /* función para almcenar las lecturas del qr del inspector */
    public function ReadQrInspector($id)
    {
        $infoInspector = Inspector::findOrFail($id);
        
        if( !CompanyController::compareCompanySession($infoInspector->companies) ){
            abort(403, 'This action is unauthorized.');        
        }

        // Se trae la información del usuario
        $usuario = User::find($infoInspector->user_id);
        $code = "";  
        return view('inspector.lectura', compact('infoInspector','usuario'));
    }

    /** funcion para almacenar la lectura del qr */
    public function saveReadInspector()
    {
        print_r($_POST);
    }
    
}

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
        /* dd(Company::find(session()->get('Session_Company'))->slug);
        dd(session()->get('Session_Company')); */
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

        if($this->compareCompanySession($inspector->companies)){
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

        if( !$this->compareCompanySession($inspector->companies) ){
            abort(403, 'This action is unauthorized.');        
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

        if( !$this->compareCompanySession($inspector->companies) ){
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

    public function companyTable($company)
    {
        $result = Inspector::query()
                ->join('company_inspector', 'company_inspector.inspector_id', '=', 'inspectors.id')
                ->join('companies', 'companies.id', '=', 'company_inspector.company_id')
                ->select('inspectors.*')
                ->where('companies.slug', '=', $company)
                ->with('companies', 'profession', 'inspectorType', 'user', 'companies.user')
                ->get();

        return datatables()
            ->of($result)
            ->addColumn('entity', 'inspectors')
            ->addColumn('action', 'id')
            ->addColumn('actions', 'shared/_actions')
            ->rawColumns(['actions'])
            ->toJson();
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
        
        if( !$this->compareCompanySession($infoInspector->companies) ){
            abort(403, 'This action is unauthorized.');        
        }

        // Se trae la información del usuario
        $usuario = User::find($infoInspector->user_id);
        $code = "";

        /**
         * El bloque soguiente es para el consumo del WS de firma 
         */
        /*$signaFirma = new WsdlFirmaController();
        $respuestaFirma = $signaFirma->autenticacionUsuario();      
        echo "<pre>";
        print_r($respuestaFirma);
        echo "</pre>",
        //$respuestaFirma = $signaFirma->autenticarUsuario();
        exit();*/
      

        /*$signaSelladoTiempo = new WsdlSelladoTiempoController();
        $signaSelladoTiempo->autenticarUsuario();*/

        /**
         * El bloque comentado acontinuacion muestra como deben de realizar las peticiones para blokchain.
         */

        /*
        $concatenado = ObtenerConcatenadoObjeto::concatenar($infoInspector);
        $hash = HashUtilidades::generarHash($concatenado);
        $hash = HashUtilidades::generarHash('HolaSOyElhash');
        $signa = new ManejadorPeticionesController();
        $obtenerToken = $signa->obtenerAuthToken();
        $obtenerToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjNiOGUwNDg3MWI5OGI5YmE3Yzg3OTk3NTNmN2FlNGY5IiwibmJmIjoxNTQyNzMxODM1LCJleHAiOjE1NDI3MzI3MzUsImlhdCI6MTU0MjczMTgzNSwiaXNzIjoiU0lHTkVCTE9DSyIsImF1ZCI6IlNJR05FQkxPQ0tfQVBJIn0.jiMRvZ6MP1L-Ourpx6R2qbCRHrS3VVz4U5Cr9a4VDlE";

        echo "<pre>";
        print_r($obtenerToken);
        echo "</pre>";
        if($obtenerToken != "")
        {
            $registrar_documento = $signa->registrarDocumento($obtenerToken,asset('images/imagenes_user/cropper.jpg'));           
            
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
        */
        return view('inspector.card', compact('infoInspector','usuario'));

    }

    /**
     * funcion para validar la información del inspector al realizar lectura QR
     */
    public static function qrInfoInspector($id)
    {
        $url = new QR_Url($_SERVER["HTTP_HOST"].'/roles-permissions/public/validateInspector/'.$id);
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
	 * Funcion para comparar la compañia es sesión con la compañia de un usuario
	 */
    public function compareCompanySession($companies){
        // Si es usuario retorne la vista
        if(auth()->user()->hasRole('Admin'))
        {
            return true;
        }
        else
        {
            
            // Recorra las compañias del inspector a consultar y comparelas con la conpañia en sesion, si es falso devuelva un mensaje de error
            foreach($companies as $company)
            {
                if($company->id == Company::findOrFail(session()->get('Session_Company'))->id)
                {
                    return true;
                }
            }
            return false;
        }
    }
}

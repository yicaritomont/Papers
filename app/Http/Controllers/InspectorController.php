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


class InspectorController extends Controller
{
    use Authorizable;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_slug=null)
    {
        if(isset($company_slug)){
            $companies = Company::select('slug', 'name')->where('slug','=',$company_slug)->get();

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
                'phone'             => 'required|string',
                'addres'            => 'required|string',
                'email'             => 'required|email|unique:users,email',
                'profession_id'     => 'required',
                'inspector_type_id' => 'required'
            ]);
        }
        else
        {
            $this->validate($request, [
                'name'              => 'bail|required|min:2',
                'identification'    => 'required|unique:inspectors|numeric',
                'phone'             => 'required|string',
                'addres'            => 'required|string',
                'email'             => 'required|email|unique:users,email',
                'profession_id'     => 'required',
                'inspector_type_id' => 'required'
            ]);
        }


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

                flash()->success(trans('words.RelationshipInspectorCompany'));
            }
            else
            {
                flash()->error(trans('words.InspectorCompany'));
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
                $user->syncPermissions($request, $user);
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
                    flash(trans('words.Inspectors').' '.trans('words.HasAdded'));
                    $inspector->companies()->attach($request->companies);
                } 
                else 
                {
                    flash()->error(trans('words.UnableCreate').' '.trans('words.Inspectors'));
                }
            }
            else
            {
                flash()->error(trans('words.UnableCreate').' '.trans('words.Inspectors'));
            }
                    
        }
        return redirect()->route('inspectors.index');


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

        return view('inspector.edit', compact('inspector', 'permissions','professions','inspector_types','countries','cities', 'companies','user'));
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
        $this->validate($request, [
            'name' => 'bail|required|min:2'
        ]);

        //Get the inspector
        $inspector = Inspector::findOrFail($id);

        if($inspector->identification != $request->identification) {

            $this->validate($request, [
            'name' => 'bail|required|min:2',
            'identification' => 'required|unique:inspectors|numeric',
            'phone' => 'required|string',
            'addres' => 'required|string',
            'email' => 'required|email',
        ]);
        }
        $inspector->fill($request->except('permissions'));
        
        $user = $inspector->user;
        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->syncPermissions($request, $user);
        $user->save();
        $user->companies()->sync($request->companies);

        $inspector->save();
        $inspector->companies()->sync($request->companies);

        flash()->success(trans_choice('words.Inspector', 1).' '.trans('words.HasUpdated'));

        return redirect()->route('inspectors.index');
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

    public function companyTable($company){

        $result = Inspector::query()
                ->join('company_inspector', 'company_inspector.inspector_id', '=', 'inspectors.id')
                ->join('companies', 'companies.id', '=', 'company_inspector.company_id')
                ->select('inspectors.*')
                ->where('companies.slug', '=', $company)
                ->with('companies', 'profession', 'inspectorType')
                ->get();

        // dd($result);

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
            $inspector = Inspector::where('identification',$_GET['idInspector'])->get();

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
        $infoInspector = Inspector::find($id);
        // Se trae la infomacion de loa relacion de usuario
        $usuarioInspector = usuario_rol::where('user',$infoInspector->id)->where('rol_id',2)->first();
        // Se trae la información del usuario
        $usuario = User::find($usuarioInspector->user_id);
        $code = "";
        /*$signa = new ManejadorPeticionesController();
        $obtenerToken = $signa->obtenerAuthToken();
        echo "<pre>";
        print_r($obtenerToken);
        echo "</pre>";
        exit();*/
        return view('inspector.card', compact('infoInspector','usuario'));

    }

    /**
     * funcion para validar la información del inspector al realizar lectura QR
     */
    public static function qrInfoInspector($id)
    {
        //$url = new QR_Url('https://werneckbh.github.io/qr-code/');
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
}
